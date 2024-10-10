<?php

namespace App\Http\Controllers;

use App\Models\ManagedCategory;
use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
class PaymentsController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $currentDate = now();

        $page_title = 'Payments';
        $setting = Setting::where('user_id', Auth::user()->id)->first();

        // Messages
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
        $categoryIds = $managedCategories->pluck('category_id');
        $categories = ManagedCategory::whereIn('id', $categoryIds)->get();
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



        $users = User::where('name', '!=', $current_user_name)->get();


        return view('admin.pages.payments', compact('users', 'unreadMessages', 'contacts', 'setting', 'page_title', 'notifications', 'unreadNotifications'));
    }
}
