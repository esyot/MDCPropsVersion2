<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemsTransaction;
use App\Models\ManagedCategory;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Rentee;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $current_user_name = Auth::user()->name;
        $currentDate = now();
        $page_title = 'Analytics';

        $users = User::whereNot('id', Auth::user()->id)->get();

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
        $itemsCount = Item::all()->count();
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
            $itemsCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->count();

            $itemsDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->count();

            $itemsCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->count();

            //canceled reservation by months
            $januaryCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 1)
                ->count();

            $februaryCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 2)
                ->count();

            $marchCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 3)
                ->count();

            $aprilCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 4)
                ->count();

            $mayCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 5)
                ->count();

            $juneCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 6)
                ->count();

            $julyCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 7)
                ->count();

            $augustCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 8)
                ->count();

            $septemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 9)
                ->count();

            $octoberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 10)
                ->count();

            $novemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 11)
                ->count();

            $decemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 12)
                ->count();

            // declined reservations by month


            $januaryDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 1)
                ->count();

            $februaryDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 2)
                ->count();

            $marchDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 3)
                ->count();

            $aprilDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 4)
                ->count();

            $mayDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 5)
                ->count();

            $juneDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 6)
                ->count();

            $julyDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 7)
                ->count();

            $augustDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 8)
                ->count();

            $septemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 9)
                ->count();

            $octoberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 10)
                ->count();

            $novemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 11)
                ->count();

            $decemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 12)
                ->count();


            // Completed reservations by month
            $januaryCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 1)
                ->count();

            $februaryCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 2)
                ->count();

            $marchCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 3)
                ->count();

            $aprilCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 4)
                ->count();

            $mayCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 5)
                ->count();

            $juneCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 6)
                ->count();

            $julyCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 7)
                ->count();

            $augustCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 8)
                ->count();

            $septemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 9)
                ->count();

            $octoberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 10)
                ->count();

            $novemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 11)
                ->count();

            $decemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 12)
                ->count();

        } else {

            $itemsCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->count();
            $itemsDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->count();
            $itemsCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->count();


            //canceled reservation by months
            $januaryCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 1)
                ->count();

            $februaryCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 2)
                ->count();

            $marchCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 3)
                ->count();

            $aprilCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 4)
                ->count();

            $mayCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 5)
                ->count();

            $juneCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 6)
                ->count();

            $julyCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 7)
                ->count();

            $augustCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 8)
                ->count();

            $septemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 9)
                ->count();

            $octoberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 10)
                ->count();

            $novemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 11)
                ->count();

            $decemberCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
                ->whereYear('canceledByRentee_at', $currentYear)
                ->whereMonth('canceledByRentee_at', 12)
                ->count();

            // declined reseravtions by month
            $januaryDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 1)
                ->count();

            $februaryDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 2)
                ->count();

            $marchDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 3)
                ->count();

            $aprilDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 4)
                ->count();

            $mayDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 5)
                ->count();

            $juneDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 6)
                ->count();

            $julyDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 7)
                ->count();

            $augustDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 8)
                ->count();

            $septemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 9)
                ->count();

            $octoberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 10)
                ->count();

            $novemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 11)
                ->count();

            $decemberDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
                ->whereYear('declinedByAdmin_at', $currentYear)
                ->whereMonth('declinedByAdmin_at', 12)
                ->count();



            // Completed reservations by month
            $januaryCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 1)
                ->count();

            $februaryCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 2)
                ->count();

            $marchCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 3)
                ->count();

            $aprilCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 4)
                ->count();

            $mayCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 5)
                ->count();

            $juneCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 6)
                ->count();

            $julyCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 7)
                ->count();

            $augustCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 8)
                ->count();

            $septemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 9)
                ->count();

            $octoberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 10)
                ->count();

            $novemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
                ->whereYear('returned_at', $currentYear)
                ->whereMonth('returned_at', 11)
                ->count();

            $decemberCompletedCount = ItemsTransaction::whereNotNull('returned_at')
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
                'itemsCount',
                'categoriesCount',
                'adminsCount',
                'superadminsCount',
                'cashiersCount',
                'staffsCount',
                'itemsCanceledCount',
                'itemsDeclinedCount',
                'itemsCompletedCount',
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
