<?php

namespace App\Http\Controllers;

use App\Models\PropertyReservation;
use App\Models\Property;
use DB;
use Illuminate\Http\Request;
use App\Models\Reservation;
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
            ->select(
                'messages.*',
                'users.*',
                'users.name as sender_name',
                'users.id as sender_id',
                'messages.created_at as created_at',
            )
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->orderBy('messages.created_at', 'desc') // Order by the most recent message first
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

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();


        } else if ($roles->contains('admin')) {

            $categories = Category::all();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])
                ->whereJsonDoesntContain(
                    'isDeletedBy',
                    Auth::user()->id
                )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();


        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('category_id', $categoryIds)->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)->whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])->count();


        }

        if ($currentCategory) {
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;

            $daysWithRecords = PropertyReservation::where('category_id', $currentCategory->id)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->flatMap(function ($reservation) {
                    // Create Carbon instances for the start and end dates
                    $startDate = Carbon::parse($reservation->date_start);
                    $endDate = Carbon::parse($reservation->date_end);

                    // Collect all dates between startDate and endDate (inclusive)
                    $dates = [];
                    while ($startDate->lte($endDate)) {
                        $dates[] = $startDate->format('Y-m-d');
                        $startDate->addDay(); // Move to the next day
                    }

                    return $dates;
                })
                ->unique()
                ->values()
                ->toArray();



        } else {

            $categoriesIsNull = true;
            $daysWithRecords = null;
        }


        $properties = $currentCategory ? Property::where('category_id', $currentCategory->id)->get() : collect();

        $users = User::where('id', '!=', $current_user_id)->get();

        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $reservations = PropertyReservation::all();

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
            'properties',
            'currentDate',
            'daysWithRecords',
            'reservations',
            'categoriesIsNull'
        ));
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

        $reservations = PropertyReservation::where('category_id', $category)->get();

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessages = $messages->count();

        $properties = Property::where('category_id', $category)->orderBy('name', 'ASC')->get();

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

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();

            $daysWithRecords = PropertyReservation::where('category_id', $category)
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

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin', 'all'])->count();

            $daysWithRecords = PropertyReservation::where('category_id', $category)
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
            })->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

            $managedCategoryIds = $categoryIds->toArray();

            if (in_array($category, $managedCategoryIds))

                $daysWithRecords = PropertyReservation::where('category_id', $currentCategory->id)
                    ->whereYear('date_start', $currentDate->format('Y'))
                    ->get()
                    ->flatMap(function ($reservation) {
                        // Create Carbon instances for the start and end dates
                        $startDate = Carbon::parse($reservation->date_start);
                        $endDate = Carbon::parse($reservation->date_end);

                        // Collect all dates between startDate and endDate (inclusive)
                        $dates = [];
                        while ($startDate->lte($endDate)) {
                            $dates[] = $startDate->format('Y-m-d');
                            $startDate->addDay(); // Move to the next day
                        }

                        return $dates;
                    })
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


        $properties = $currentCategory ? Property::where('category_id', $currentCategory->id)->get() : collect();



        $users = User::where('name', '!=', $current_user_id)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $reservations = PropertyReservation::all();

        return view(
            'admin.partials.calendar',
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
                'properties',
                'currentCategory',
                'categories',
                'currentDate',
                'reservations',
                'daysWithRecords'
            )
        );
    }



    public function calendarMove($action, $category, $year, $month)
    {
        $currentDate = Carbon::create($year, $month, 1);

        if ($action === 'left') {
            $currentDate->subYear();
        } elseif ($action === 'right') {
            $currentDate->addYear();
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



        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = Category::find($category);
        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('superadmin')) {
            $categories = Category::all();

            $currentCategory = Category::find($category);

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'supperadmin|admin', 'all'])->count();

            $daysWithRecords = PropertyReservation::where('category_id', $category)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->flatMap(function ($reservation) {
                    // Create Carbon instances for the start and end dates
                    $startDate = Carbon::parse($reservation->date_start);
                    $endDate = Carbon::parse($reservation->date_end);

                    // Collect all dates between startDate and endDate (inclusive)
                    $dates = [];
                    while ($startDate->lte($endDate)) {
                        $dates[] = $startDate->format('Y-m-d');
                        $startDate->addDay(); // Move to the next day
                    }

                    return $dates;
                })
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

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();

            $daysWithRecords = PropertyReservation::where('category_id', $category)
                ->whereYear('date_start', $currentDate->format('Y'))
                ->get()
                ->flatMap(function ($reservation) {
                    // Create Carbon instances for the start and end dates
                    $startDate = Carbon::parse($reservation->date_start);
                    $endDate = Carbon::parse($reservation->date_end);

                    // Collect all dates between startDate and endDate (inclusive)
                    $dates = [];
                    while ($startDate->lte($endDate)) {
                        $dates[] = $startDate->format('Y-m-d');
                        $startDate->addDay(); // Move to the next day
                    }

                    return $dates;
                })
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
            })->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

            $managedCategoryIds = $categoryIds->toArray();

            if (in_array($category, $managedCategoryIds))

                $daysWithRecords = PropertyReservation::where('category_id', $category)
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


        $properties = $currentCategory ? Property::where('category_id', $currentCategory->id)->get() : collect();

        $users = User::where('name', '!=', $current_user_id)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = PropertyReservation::all();

        $currentCategory = Category::find($category);

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
            'properties',
            'currentDate',
            'daysWithRecords',
            'transactions',
            'categoriesIsNull',
            'currentCategory'
        ));
    }
}
