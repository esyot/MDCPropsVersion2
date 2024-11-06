<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
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
    public function index()
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
                'categoriesCount'
            )
        );
    }
}
