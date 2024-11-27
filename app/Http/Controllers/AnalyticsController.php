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

    public function custom($rentee, $category, $property, $destination, $year, $month, $day)
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


        if (in_array('all', [$rentee, $category, $property, $destination, $year, $month, $day])) {
            $records = PropertyReservation::all();
        }


        // Lists of Arrays

        $rentees = Rentee::all();
        $categories = Category::all();
        $properties = Property::all();


        return view(
            'admin.analytics.customized',
            compact(
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

                'currentYear',

            )
        );
    }

}
