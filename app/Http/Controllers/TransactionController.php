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
            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::where('isRead', false)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        } else {
            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereIn('for', ['admin', 'both'])->where('isRead', false)->count();

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

        if ($roles->contains('moderator') || $roles->contains('editor')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::where('isRead', false)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        } else if ($roles->contains('admin')) {
            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereIn('for', ['admin', 'both'])->where('isRead', false)->count();

        }
        $users = User::whereNot('name', Auth::user()->name)->get();


        return view('admin.pages.transactions', compact('users', 'categoriesIsNull', 'currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));


    }
    public function create(Request $request)
    {
        $currentUserName = Auth::user()->name;

        // Validate the incoming request data
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id', // Check if item exists
            'category_id' => 'required|exists:categories,id', // Check if category exists
            'rentee_name' => 'required|string|max:255',
            'rentee_contact_no' => 'required|string|max:255',
            'rentee_email' => 'required|string|email|max:255',
            'destination_id' => 'required|exists:destinations,id', // Check if destination exists
            'rent_date' => 'required|date',
            'rent_time' => 'required|date_format:H:i',
            'rent_return' => 'required|date|after_or_equal:rent_date', // Ensure valid return date
            'rent_return_time' => 'required|date_format:H:i',
        ]);

        try {
            // Create the transaction
            Transaction::create(array_merge($validatedData, ['status' => 'pending']));

            // Create a notification
            Notification::create([
                'category_id' => $request->category_id,
                'icon' => Auth::user()->img,
                'title' => "New Transaction",
                'description' => "$currentUserName added a new transaction, check it now.",
                'redirect_link' => "transactions",
                'for' => 'both'
            ]);

            return redirect()->back()->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Transaction creation error: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Error creating transaction. Please try again later.');
        }
    }
}
