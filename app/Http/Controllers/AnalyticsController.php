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


        $currentYear = $request->year ?? Carbon::now()->format('Y');

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

            // Initialize arrays for storing the counts
            $canceledCounts = array_fill(0, 12, 0);
            $declinedCounts = array_fill(0, 12, 0);
            $completedCounts = array_fill(0, 12, 0);

            // Query for canceled reservations by month
            $canceledReservations = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->selectRaw('MONTH(canceledByRentee_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get();

            // Query for declined reservations by month
            $declinedReservations = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->selectRaw('MONTH(declinedByAdmin_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get();

            // Query for completed reservations by month
            $completedReservations = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->selectRaw('MONTH(returned_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get();

            // Populate the arrays with the counts from the queries
            foreach ($canceledReservations as $reservation) {
                $canceledCounts[$reservation->month - 1] = $reservation->count;
            }

            foreach ($declinedReservations as $reservation) {
                $declinedCounts[$reservation->month - 1] = $reservation->count;
            }

            foreach ($completedReservations as $reservation) {
                $completedCounts[$reservation->month - 1] = $reservation->count;
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

        $current_user_id = Auth::user()->id;
        $currentDate = now();
        $page_title = 'Analytics';
        $users = User::whereNot('id', $current_user_id)->get();

        $messages = Message::where('receiver_id', $current_user_id)
            ->where('isReadByReceiver', false)
            ->get();

        $unreadMessages = $messages->count();

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


        $categoriesIsNull = false;
        if ($currentCategory) {
            $currentCategoryId = $currentCategory->id;
        } else {
            $categoriesIsNull = true;
        }


        $currentDateStart = $request->date_start ?? 'all';
        $currentDateEnd = $request->date_end ?? 'all';

        $currentYear = Carbon::parse($currentDate)->format('Y');


        $rentees = Rentee::all();


        $currentRentee = null;
        if ($request->rentee == null) {
            $request->rentee = 'all';
        }

        $properties = Property::all();

        $selectedCategory = null;


        $recordsQuery = PropertyReservation::with([
            'category',
            'property',
            'reservation.rentee'
        ]);


        if ($request->rentee != 'all') {
            $currentRentee = $rentees->first();
            $recordsQuery->whereHas('reservation', function ($query) use ($currentRentee) {
                $query->where('rentee_id', $currentRentee->id);
            });
        }
        $selectedProperty = null;


        if ($request->category != 'all') {
            $recordsQuery->whereHas('category', function ($query) use ($request) {
                $query->where('category_id', $request->category);
            });

            $selectedCategory = Category::find($request->category);
            $properties = Property::where('category_id', $request->category)->get();
            $selectedProperty = Property::where('category_id', $request->category)
                ->first();
        }

        if ($request->property != 'all' && $request->category != 'all') {
            $selectedProperty = Property::where('category_id', $request->category)
                ->where('id', $request->property)->first();

            if ($selectedProperty == null) {

                $selectedProperty = Property::where('category_id', $request->category)->first();

                $properties = Property::where('category_id', $request->category)->get();
            }

        }


        if ($currentDateStart != 'all') {
            $recordsQuery->where('date_start', '<=', $currentDateStart);
        }

        if ($currentDateEnd != 'all') {
            $recordsQuery->where('date_end', $currentDateEnd);
        }

        if ($currentDateEnd != 'all' && $currentDateStart != 'all') {
            $recordsQuery->where('date_end', '<=', $currentDateEnd)
                ->where('date_start', '>=', $currentDateStart);
        }


        $records = $recordsQuery->get();
        $categories = Category::all();

        $clonedQueryForCanceled = clone $recordsQuery;
        $clonedQueryForDeclined = clone $recordsQuery;
        $clonedQueryForCompleted = clone $recordsQuery;


        $propertiesCanceledCount = $clonedQueryForCanceled->whereNotNull('canceledByRentee_at')->count();
        $propertiesDeclinedCount = $clonedQueryForDeclined->whereNotNull('declinedByAdmin_at')->count();
        $propertiesCompletedCount = $clonedQueryForCompleted->whereNotNull('returned_at')->count();

        $canceledCounts = [];
        $declinedCounts = [];
        $completedCounts = [];

        for ($month = 1; $month <= 12; $month++) {

            $clonedQueryForCanceled = clone $recordsQuery;
            $clonedQueryForDeclined = clone $recordsQuery;
            $clonedQueryForCompleted = clone $recordsQuery;

            $canceledCounts[] = $clonedQueryForCanceled
                ->whereNotNull('canceledByRentee_at')
                ->whereMonth('canceledByRentee_at', $month)
                ->count();

            $declinedCounts[] = $clonedQueryForDeclined
                ->whereNotNull('declinedByAdmin_at')
                ->whereMonth('declinedByAdmin_at', $month)
                ->count();

            $completedCounts[] = $clonedQueryForCompleted
                ->whereNotNull('returned_at')
                ->whereMonth('returned_at', $month)
                ->count();
        }

        return view(
            'admin.analytics.customized',
            compact(
                'propertiesCanceledCount',
                'propertiesDeclinedCount',
                'propertiesCompletedCount',
                'canceledCounts',
                'declinedCounts',
                'completedCounts',
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
                'currentDateStart',
                'currentDateEnd',
                'currentYear',
                'selectedProperty'
            )
        );
    }


}