<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;

use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $current_user_name = Auth::user()->name;

        $transactions = Transaction::where('category_id', 1)->where('status', 'pending')->get();

        $unreadNotifications = Notification::where('isRead', false)->get()->count();
        $notifications = Notification::orderBy('created_at', 'DESC')->get();

        $categories = Category::all();


        $page_title = 'Transactions';

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();

        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();


        $currentStatus = 'pending';

        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('staff')) {

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

        } else {
            $categories = Category::all();
            $currentCategory = $categories->first();
        }

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        return view('admin.pages.transactions', compact('users', 'categoriesIsNull', 'currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));

    }

    public function decline($id)
    {

        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        if ($transaction) {

            return redirect()->back()->with('success', 'Transaction has been successfully declined!');
        }


    }

    public function approve($id)
    {

        $transaction = Transaction::find($id);

        $transaction->update([
            'status' => 'approved',
        ]);

        if ($transaction) {
            return redirect()->back()->with('success', 'Transaction has been successfuly approved!');
        }

    }

    public function filter(Request $request)
    {

        $transactions = Transaction::where('status', $request->status)->where('category_id', $request->category)->get();

        $current_user_name = Auth::user()->name;

        $category = $request->category;

        $unreadNotifications = Notification::where('isRead', false)->get()->count();
        $notifications = Notification::orderBy('created_at', 'DESC')->get();

        $categories = Category::all();


        $page_title = 'Transactions';

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();

        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $currentCategory = Category::find($request->category);

        $currentStatus = $request->status;
        $roles = Auth::user()->getRoleNames();

        $categoriesIsNull = false;

        if ($roles->contains('staff')) {

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id'); // Get the category IDs
            $categories = Category::whereIn('id', $categoryIds)->get(); // Fetch the categories
            $currentCategory = Category::find($category);


        } else {
            $categories = Category::all();
            $currentCategory = Category::find($category);
            $categoriesIsNull = false;

        }
        $users = User::whereNot('name', Auth::user()->name)->get();


        return view('admin.pages.transactions', compact('users', 'categoriesIsNull', 'currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));


    }
}
