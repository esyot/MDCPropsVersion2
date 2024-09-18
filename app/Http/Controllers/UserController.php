<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Message;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $currentUserName = $currentUser->name;
        $defaultCategoryId = 1;

        // Fetch necessary data
        $users = User::all();
        $roles = Role::all();
        $setting = Setting::find(1);
        $currentCategory = Category::find($defaultCategoryId);
        $transactions = Transaction::where('category_id', $defaultCategoryId)->get();
        $categories = Category::orderBy('id')->get();
        $items = Item::where('category_id', $defaultCategoryId)->get();

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotificationsCount = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $currentUserName)->where('isRead', false)->get();
        $unreadMessagesCount = $messages->count();

        $contacts = Message::where('receiver_name', $currentUserName)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        return view('pages.users', [
            'roles' => $roles,
            'users' => $users,
            'setting' => $setting,
            'current_user_name' => $currentUserName,
            'contacts' => $contacts,
            'unreadMessages' => $unreadMessagesCount,
            'page_title' => 'Dashboard',
            'unreadNotifications' => $unreadNotificationsCount,
            'notifications' => $notifications,
            'items' => $items,
            'currentCategory' => $currentCategory,
            'categories' => $categories,
            'currentDate' => now(),
            'transactions' => $transactions,
        ]);
    }

    public function roleUpdate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role]); // Update user roles

        return redirect()->back()->with('success', 'User role updated successfully!');
    }
}
