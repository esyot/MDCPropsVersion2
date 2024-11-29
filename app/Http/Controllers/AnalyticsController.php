<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\PropertyReservation;
use App\Models\ManagedCategory;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Rentee;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $current_user_id = Auth::user()->id;
        $currentDate = now();
        $page_title = 'Analytics';

        $users = User::whereNot('id', Auth::user()->id)->get();

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();

        $unreadMessages = Message::where('receiver_id', Auth::user()->id)
            ->where('isReadByReceiver', false)
            ->count();

        $unreadMessagesCount = $messages->count();

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

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();

        }

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }

        $usersCount = User::all()->count();
        $renteesCount = Rentee::all()->count();
        $propertiesCount = Property::all()->count();
        $categoriesCount = Category::all()->count();

        $adminsCount = User::role('admin')->count();

        $superadminsCount = User::role('superadmin')->count();

        $cashiersCount = User::role('cashier')->count();

        $staffsCount = User::role('staff')->count();


        $currentYear = $request->year ?? date('Y');


        if ($request->year) {
            $propertiesCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->count();

            $propertiesDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->count();

            $propertiesCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->count();

            $canceledCounts = [];
            $declinedCounts = [];
            $completedCounts = [];

            for ($month = 1; $month <= 12; $month++) {

                $canceledCounts[] = PropertyReservation::whereNotNull('canceledByRentee_at')
                    ->whereYear('canceledByRentee_at', $currentYear)
                    ->whereMonth('canceledByRentee_at', $month)
                    ->count();


                $declinedCounts[] = PropertyReservation::whereNotNull('declinedByAdmin_at')
                    ->whereYear('declinedByAdmin_at', $currentYear)
                    ->whereMonth('declinedByAdmin_at', $month)
                    ->count();


                $completedCounts[] = PropertyReservation::whereNotNull('returned_at')
                    ->whereYear('returned_at', $currentYear)
                    ->whereMonth('returned_at', $month)
                    ->count();
            }

        } else {

            $propertiesCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->count();
            $propertiesDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->count();
            $propertiesCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->count();

            $canceledCounts = [];
            $declinedCounts = [];
            $completedCounts = [];


            for ($month = 1; $month <= 12; $month++) {

                $canceledCounts[] = PropertyReservation::whereNotNull('canceledByRentee_at')
                    ->whereYear('canceledByRentee_at', $currentYear)
                    ->whereMonth('canceledByRentee_at', $month)
                    ->count();


                $declinedCounts[] = PropertyReservation::whereNotNull('declinedByAdmin_at')
                    ->whereYear('declinedByAdmin_at', $currentYear)
                    ->whereMonth('declinedByAdmin_at', $month)
                    ->count();


                $completedCounts[] = PropertyReservation::whereNotNull('declinedByAdmin_at')
                    ->whereYear('returned_at', $currentYear)
                    ->whereMonth('returned_at', $month)
                    ->count();
            }

        }

        return view(
            'admin.pages.analytics',
            compact(
                'declinedCounts',
                'canceledCounts',
                'completedCounts',
                'currentCategory',
                'setting',
                'page_title',
                'unreadNotifications',
                'notifications',
                'unreadMessages',
                'contacts',
                'users',
                'usersCount',
                'renteesCount',
                'propertiesCount',
                'categoriesCount',
                'adminsCount',
                'superadminsCount',
                'cashiersCount',
                'staffsCount',
                'propertiesCanceledCount',
                'propertiesDeclinedCount',
                'propertiesCompletedCount',
                'currentYear',

            )
        );
    }

    public function custom(Request $request)
    {
        // Set basic variables
        $current_user_id = Auth::user()->id;
        $currentDate = now();
        $page_title = 'Analytics';

        // Get users excluding the current one
        $users = User::whereNot('id', $current_user_id)->get();

        // Get unread messages for the current user
        $messages = Message::where('receiver_id', $current_user_id)
            ->where('isReadByReceiver', false)
            ->get();

        $unreadMessages = $messages->count();

        // Get recent message contacts (last message per sender)
        $contacts = DB::table('messages')
            ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where('messages.receiver_id', $current_user_id)
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->get();

        // Get user settings
        $setting = Setting::where('user_id', $current_user_id)->first();

        // Get roles of the current user
        $roles = Auth::user()->getRoleNames();

        // Initialize variables for categories and notifications
        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

        // Determine the categories and notifications based on roles
        if ($roles->contains('superadmin')) {
            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])
                ->whereJsonDoesntContain('isDeletedBy', $current_user_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', $current_user_id)
                ->whereJsonDoesntContain('isDeletedBy', $current_user_id)
                ->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])
                ->count();

        } else if ($roles->contains('admin')) {
            // Admin role: filter based on managed categories
            $managedCategories = ManagedCategory::where('user_id', $current_user_id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])
                ->whereJsonDoesntContain('isDeletedBy', $current_user_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', $current_user_id)
                ->whereJsonDoesntContain('isDeletedBy', $current_user_id)
                ->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])
                ->count();
        }

        // Determine if there's a current category or if it's null
        $categoriesIsNull = false;
        if ($currentCategory) {
            $currentCategoryId = $currentCategory->id;
        } else {
            $categoriesIsNull = true;
        }

        // Get user and category counts
        $usersCount = User::all()->count();
        $renteesCount = Rentee::all()->count();
        $propertiesCount = Property::all()->count();
        $categoriesCount = Category::all()->count();
        $adminsCount = User::role('admin')->count();
        $superadminsCount = User::role('superadmin')->count();
        $cashiersCount = User::role('cashier')->count();
        $staffsCount = User::role('staff')->count();

        // Date filters (defaults to today's date if not provided)
        $currentDateStart = $request->date_start ?? 'all';
        $currentDateEnd = $request->date_end ?? 'all';
        $currentYear = Carbon::parse($currentDate)->format('Y');

        // Get rentees for filtering
        $rentees = Rentee::all();
        $currentRentee = null;
        if ($request->rentee == null) {
            $request->rentee = 'all';
        }

        $selectedCategory = null;

        // Start building the PropertyReservation query
        $recordsQuery = PropertyReservation::with([
            'category',
            'property',
            'reservation.rentee'
        ]);

        // Apply rentee filter if not 'all'
        if ($request->rentee != 'all') {
            $currentRentee = $rentees->first(); // Assuming the first is selected for now
            $recordsQuery->whereHas('reservation', function ($query) use ($currentRentee) {
                $query->where('rentee_id', $currentRentee->id);
            });
        }

        // Apply category filter if not 'all'
        if ($request->category != 'all') {
            $recordsQuery->whereHas('category', function ($query) use ($request) {
                $query->where('category_id', $request->category);
            });
        }

        // Apply property filter if not 'all'
        $selectedProperty = null;
        if ($request->property != 'all') {
            $selectedProperty = Property::find($request->property); // Fetch the selected property
            $recordsQuery->where('property_id', $request->property); // Filter by property ID
        }

        // Apply date filters if provided
        if ($currentDateStart != 'all') {
            $recordsQuery->where('date_start', $currentDateStart);
        }

        if ($currentDateEnd != 'all') {
            $recordsQuery->where('date_end', $currentDateEnd);
        }

        // Execute the query
        $records = $recordsQuery->get();

        // Final categories and properties lists for the view
        $categories = Category::all();
        $properties = Property::all();

        // Return the view with the data
        return view(
            'admin.analytics.customized',
            compact(
                'selectedCategory',
                'currentRentee',
                'properties',
                'categories',
                'rentees',
                'records',
                'currentCategory',
                'setting',
                'page_title',
                'unreadNotifications',
                'notifications',
                'unreadMessages',
                'contacts',
                'users',
                'usersCount',
                'renteesCount',
                'propertiesCount',
                'categoriesCount',
                'adminsCount',
                'superadminsCount',
                'cashiersCount',
                'staffsCount',
                'currentDateStart',
                'currentDateEnd',
                'currentYear',
                'selectedProperty' // Add selectedProperty to the view
            )
        );
    }
}