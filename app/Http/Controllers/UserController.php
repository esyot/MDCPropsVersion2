<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\ManagedCategory;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Hash;
use Log;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $currentUserName = $currentUser->name;
        $defaultCategoryId = 1;

        $users = User::all();
        $roles = Role::all();
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        $currentCategory = Category::find($defaultCategoryId);
        $transactions = Transaction::where('category_id', $defaultCategoryId)->get();
        $categories = Category::orderBy('id')->get();
        $items = Item::where('category_id', $defaultCategoryId)->get();


        $messages = Message::where('receiver_name', $currentUserName)->where('isRead', false)->get();
        $unreadMessagesCount = $messages->count();

        $contacts = Message::where('receiver_name', $currentUserName)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();


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


        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }


        return view('admin.pages.users', [
            'roles' => $roles,
            'users' => $users,
            'setting' => $setting,
            'current_user_name' => $currentUserName,
            'contacts' => $contacts,
            'unreadMessages' => $unreadMessagesCount,
            'page_title' => 'Manage Users',
            'unreadNotifications' => $unreadNotifications,
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
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User role has been updated successfully!');
    }

    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make(value: 'P@ssw0rd'),
        ]);


        $role = Role::findByName('staff');
        $user->assignRole($role);

        Notification::create([
            'icon' => Auth::user()->img,
            'title' => 'Added a new user',
            'description' => Auth::user()->name . ' added a new user ' . $request->name,
            'redirect_link' => 'users',
            'for' => 'both',

        ]);

        $newuser = User::latest()->first();

        Setting::create([
            'user_id' => $newuser->id,
            'darkMode' => false,
            'transition' => true,

        ]);

        return redirect()->back()->with('success', 'A new user has been added successfully!');


    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        if ($user) {

            return redirect()->back()->with('success', 'A user has been deleted successfully!');
        }

    }

    public function filter(Request $request)
    {

        if ($request->search == null) {

            $users = User::all();

            return view('admin.partials.users', compact('users'));
        } else {

            $users = User::where('name', 'LIKE', '%' . $request->search . '%')->orwhere('email', 'LIKE', '%' . $request->search . '%')->get();

            return view('admin.partials.users', compact('users'));
        }

    }

}
