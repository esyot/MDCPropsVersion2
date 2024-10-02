<?php

namespace App\Http\Controllers;

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
        $currentDate = now();
        $page_title = 'Dashboard';

        // Messages
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $roles = Auth::user()->getRoleNames();

        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

        if ($roles->contains('moderator') || $roles->contains('editor')) {
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

        } else if ($roles->contains('admin')) {
            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();

        } else if ($roles->contains('viewer')) {
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
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }

        // Safely get items only if currentCategory exists
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();

        // Transactions and records
        $daysWithRecords = Transaction::all()->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))->unique()->values()->toArray();

        // Users and destinations
        $users = User::where('name', '!=', $current_user_name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = Transaction::all();

        return view('admin.pages.dashboard', compact(
            'categories',
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
        $transactions = Transaction::where('rent_date', $date)->get();

        $setting = Setting::find(1);

        return view('admin.partials.date-view', compact('roles', 'setting', 'transactions', 'date'));
    }

    public function dateCustom(Request $request)
    {
        $page_title = "Dashboard";
        $current_user_name = Auth::user()->name;

        $year = $request->year;
        $month = $request->month;
        $category = $request->category;

        $currentDate = Carbon::create($year, $month, 25);

        $transactions = Transaction::where('category_id', $category)->get();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $daysWithRecords = $transactions->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $items = Item::where('category_id', $category)->orderBy('name', 'ASC')->get();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();


        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();


        if ($roles->contains('moderator') || $roles->contains('editor')) {
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

        } else if ($roles->contains('admin')) {
            $categories = Category::all();
            $currentCategory = Category::find($request->category);

            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();
            $categoriesIsNull = false;
        }





        $users = User::whereNot('name', Auth::user()->name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

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
        //date
        $currentDate = Carbon::create($year, $month, 1);

        if ($action === 'left') {
            $currentDate->subMonth();
        } elseif ($action === 'right') {
            $currentDate->addMonth();
        } elseif ($action === 'today') {
            $currentDate = now();
        }
        $current_user_name = Auth::user()->name;
        $page_title = 'Dashboard';


        // Messages
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = $messages->groupBy('sender_name')->map(fn($group) => $group->first())->values();

        // Settings and roles
        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();


        if ($roles->contains('moderator') || $roles->contains('editor')) {
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

        } else if ($roles->contains('admin')) {
            $categories = Category::all();
            $currentCategory = Category::find($category);

            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();
            $categoriesIsNull = false;
        }


        // Safely get items only if currentCategory exists
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();

        // Transactions and records
        $daysWithRecords = Transaction::all()->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))->unique()->values()->toArray();

        // Users and destinations
        $users = User::where('name', '!=', $current_user_name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = Transaction::all();

        return view('admin.partials.calendar', compact(
            'categories',
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
}
