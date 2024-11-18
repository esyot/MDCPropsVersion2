<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use App\Models\Property;
use App\Models\PropertyReservation;
use DB;
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

        $current_user_id = Auth::user()->id;

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
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




        $defaultCategoryId = 1;

        $users = User::whereNot('id', Auth::user()->id)->get();
        $roles = Role::all();
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        $currentCategory = Category::find($defaultCategoryId);
        $transactions = PropertyReservation::where('category_id', $defaultCategoryId)->get();
        $categories = Category::orderBy('id')->get();
        $properties = Property::where('category_id', $defaultCategoryId)->get();




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
            'current_user_id' => $current_user_id,
            'contacts' => $contacts,
            'unreadMessages' => $unreadMessagesCount,
            'page_title' => 'Manage Users',
            'unreadNotifications' => $unreadNotifications,
            'notifications' => $notifications,

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

        // Notification::create([
        //     'icon' => Auth::user()->img,
        //     'title' => 'Added a new user',
        //     'description' => Auth::user()->name . ' added a new user ' . $request->name,
        //     'redirect_link' => 'users',
        //     'for' => 'both',

        // ]);

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

            $users = User::whereNot('id', Auth::user()->id)->get();

            return view('admin.partials.users', compact('users'));
        } else {

            $users = User::where('name', 'LIKE', '%' . $request->search . '%')
                ->whereNot('id', Auth::user()->id)->get();

            return view('admin.partials.users', compact('users'));
        }

    }

}
