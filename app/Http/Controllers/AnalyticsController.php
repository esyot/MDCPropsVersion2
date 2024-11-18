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

        // Messages

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
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }


        $usersCount = User::all()->count();
        $renteesCount = Rentee::all()->count();
        $propertiesCount = Property::all()->count();
        $categoriesCount = Category::all()->count();


        $adminsCount = User::role('admin')->count();

        $superadminsCount = User::role('superadmin')->count();

        $cashiersCount = User::role('cashier')->count();

        $staffsCount = User::role('staff')->count();

        // Default to the current year if 'year' is not provided in the request
        $currentYear = $request->year ?? date('Y');

        // Calculate counts based on the presence of a specific year in the request
        if ($request->year) {
            // If the 'year' is set in the request, filter by year
            $propertiesCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->count();

            $propertiesDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->count();

            $propertiesCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->count();

            //canceled reservation by months
            $januaryCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 1)
                ->count();

            $februaryCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 2)
                ->count();

            $marchCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 3)
                ->count();

            $aprilCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 4)
                ->count();

            $mayCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 5)
                ->count();

            $juneCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 6)
                ->count();

            $julyCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 7)
                ->count();

            $augustCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 8)
                ->count();

            $septemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 9)
                ->count();

            $octoberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 10)
                ->count();

            $novemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 11)
                ->count();

            $decemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 12)
                ->count();

            // declined reservations by month


            $januaryDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 1)
                ->count();

            $februaryDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 2)
                ->count();

            $marchDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 3)
                ->count();

            $aprilDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 4)
                ->count();

            $mayDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 5)
                ->count();

            $juneDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 6)
                ->count();

            $julyDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 7)
                ->count();

            $augustDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 8)
                ->count();

            $septemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 9)
                ->count();

            $octoberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 10)
                ->count();

            $novemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 11)
                ->count();

            $decemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 12)
                ->count();


            // Completed reservations by month
            $januaryCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 1)
                ->count();

            $februaryCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 2)
                ->count();

            $marchCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 3)
                ->count();

            $aprilCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 4)
                ->count();

            $mayCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 5)
                ->count();

            $juneCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 6)
                ->count();

            $julyCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 7)
                ->count();

            $augustCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 8)
                ->count();

            $septemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 9)
                ->count();

            $octoberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 10)
                ->count();

            $novemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 11)
                ->count();

            $decemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 12)
                ->count();

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


            //canceled reservation by months
            $januaryCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 1)
                ->count();

            $februaryCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 2)
                ->count();

            $marchCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 3)
                ->count();

            $aprilCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 4)
                ->count();

            $mayCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 5)
                ->count();

            $juneCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 6)
                ->count();

            $julyCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 7)
                ->count();

            $augustCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 8)
                ->count();

            $septemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 9)
                ->count();

            $octoberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 10)
                ->count();

            $novemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 11)
                ->count();

            $decemberCanceledCount = PropertyReservation::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 12)
                ->count();

            // declined reseravtions by month
            $januaryDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 1)
                ->count();

            $februaryDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 2)
                ->count();

            $marchDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 3)
                ->count();

            $aprilDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 4)
                ->count();

            $mayDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 5)
                ->count();

            $juneDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 6)
                ->count();

            $julyDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 7)
                ->count();

            $augustDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 8)
                ->count();

            $septemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 9)
                ->count();

            $octoberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 10)
                ->count();

            $novemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 11)
                ->count();

            $decemberDeclinedCount = PropertyReservation::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 12)
                ->count();



            // Completed reservations by month
            $januaryCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 1)
                ->count();

            $februaryCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 2)
                ->count();

            $marchCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 3)
                ->count();

            $aprilCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 4)
                ->count();

            $mayCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 5)
                ->count();

            $juneCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 6)
                ->count();

            $julyCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 7)
                ->count();

            $augustCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 8)
                ->count();

            $septemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 9)
                ->count();

            $octoberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 10)
                ->count();

            $novemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 11)
                ->count();

            $decemberCompletedCount = PropertyReservation::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 12)
                ->count();

        }





        return view(
            'admin.pages.analytics',
            compact(
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
                'januaryCanceledCount',
                'februaryCanceledCount',
                'marchCanceledCount',
                'aprilCanceledCount',
                'mayCanceledCount',
                'juneCanceledCount',
                'julyCanceledCount',
                'augustCanceledCount',
                'septemberCanceledCount',
                'octoberCanceledCount',
                'novemberCanceledCount',
                'decemberCanceledCount',
                'januaryDeclinedCount',
                'februaryDeclinedCount',
                'marchDeclinedCount',
                'aprilDeclinedCount',
                'mayDeclinedCount',
                'juneDeclinedCount',
                'julyDeclinedCount',
                'augustDeclinedCount',
                'septemberDeclinedCount',
                'octoberDeclinedCount',
                'novemberDeclinedCount',
                'decemberDeclinedCount',
                'januaryCompletedCount',
                'februaryCompletedCount',
                'marchCompletedCount',
                'aprilCompletedCount',
                'mayCompletedCount',
                'juneCompletedCount',
                'julyCompletedCount',
                'augustCompletedCount',
                'septemberCompletedCount',
                'octoberCompletedCount',
                'novemberCompletedCount',
                'decemberCompletedCount'

            )
        );
    }


}
