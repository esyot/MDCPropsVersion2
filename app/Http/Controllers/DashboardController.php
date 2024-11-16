<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use DB;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Models\Destination;
use App\Models\ManagedCategory;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $current_user_id = Auth::user()->id;
        $currentDate = now();
        $page_title = 'Dashboard';

        $contacts = DB::table('messages')
            ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->get();


        $unreadMessages = Message::where('receiver_id', Auth::user()->id)
            ->where('isReadByReceiver', false)
            ->count();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $roles = Auth::user()->getRoleNames();

        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

        if ($roles->contains('superadmin')) {

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all'])->count();


        } else if ($roles->contains('admin')) {

            $categories = Category::all();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'all'])->count();


        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('category_id', $categoryIds)->whereIn('for', ['staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)->whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['staff', 'all'])->count();


        }

        if ($currentCategory) {
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
            $daysWithRecords = ItemsTransaction::where('category_id', $currentCategory->id)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))
                ->unique()
                ->values()
                ->toArray();
        } else {

            $categoriesIsNull = true;
            $daysWithRecords = null;
        }


        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();





        $users = User::where('id', '!=', $current_user_id)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = ItemsTransaction::all();

        $selectedMonth = '';


        return view('admin.pages.dashboard', compact(
            'categories',
            'selectedMonth',
            'destinations',
            'users',
            'currentCategory',
            'roles',
            'setting',
            'current_user_name',
            'contacts',
            'unreadMessages',
            'page_title',
            'unreadNotifications',
            'notifications',
            'items',
            'currentDate',
            'daysWithRecords',
            'transactions',
            'categoriesIsNull'
        ));
    }


    public function dateView($date)
    {
        $roles = Auth::user()->getRoleNames();
        $transactions = ItemsTransaction::where('date_start', $date)->get();

        $setting = Setting::find(1);

        return view('admin.partials.date-view', compact('roles', 'setting', 'transactions', 'date'));
    }

    public function dateCustom(Request $request)
    {
        $page_title = "Dashboard";
        $current_user_id = Auth::user()->id;

        $year = $request->year;
        $month = $request->month;
        $category = $request->category;

        $currentCategory = Category::find($category);


        $currentDate = Carbon::create($year, $month, 25);

        $transactions = ItemsTransaction::where('category_id', $category)->get();

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessages = $messages->count();




        $items = Item::where('category_id', $category)->orderBy('name', 'ASC')->get();

        $contacts = DB::table('messages')
            ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->get();


        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('superadmin')) {
            $categories = Category::all();

            $currentCategory = Category::find($category);

            $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all'])->count();

            $daysWithRecords = ItemsTransaction::where('category_id', $category)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))
                ->unique()
                ->values()
                ->toArray();

        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = Category::find($category);

            $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();

            $daysWithRecords = ItemsTransaction::where('category_id', $category)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))
                ->unique()
                ->values()
                ->toArray();


        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = Category::find($category);

            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

            $managedCategoryIds = $categoryIds->toArray();

            if (in_array($category, $managedCategoryIds))

                $daysWithRecords = ItemsTransaction::where('category_id', $category)
                    ->whereYear('date_start', $currentDate->format('Y'))
                    ->get()
                    ->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))
                    ->unique()
                    ->values()
                    ->toArray();
            else {
                return redirect()->back()->with('error', 'You have not granted access to this category!');
            }

        }
        if ($currentCategory) {
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }


        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();



        $users = User::where('name', '!=', $current_user_id)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = ItemsTransaction::all();

        return view(
            'admin.pages.dashboard',
            compact(
                'categoriesIsNull',
                'destinations',
                'users',
                'roles',
                'categories',
                'setting',
                'contacts',
                'unreadMessages',
                'page_title',
                'notifications',
                'unreadNotifications',
                'items',
                'currentCategory',
                'categories',
                'currentDate',
                'transactions',
                'daysWithRecords'
            )
        );
    }



    public function calendarMove($action, $category, $year, $month)
    {
        $currentDate = Carbon::create($year, $month, 1);

        if ($action === 'left') {
            $currentDate->subMonth();
        } elseif ($action === 'right') {
            $currentDate->addMonth();
        } elseif ($action === 'today') {
            $currentDate = now();
        }
        $current_user_id = Auth::user()->id;

        $page_title = 'Dashboard';

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessages = $messages->count();
        $contacts = DB::table('messages')
            ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->get();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $roles = Auth::user()->getRoleNames();

        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

        if ($roles->contains('superadmin')) {
            $categories = Category::all();

            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();



        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        }

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }

        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();

        $daysWithRecords = ItemsTransaction::all()->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))->unique()->values()->toArray();

        $users = User::where('name', '!=', $current_user_id)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = ItemsTransaction::all();

        return view('admin.partials.calendar', compact(
            'categories',
            'destinations',
            'users',
            'currentCategory',
            'roles',
            'setting',
            'current_user_id',
            'contacts',
            'unreadMessages',
            'page_title',
            'unreadNotifications',
            'notifications',
            'items',
            'currentDate',
            'daysWithRecords',
            'transactions',
            'categoriesIsNull'
        ));
    }
}
