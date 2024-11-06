<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;

use Illuminate\Support\Facades\Auth;
use Log;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $current_user_name = Auth::user()->name;

        $category = Category::first();

        $transactions = ItemsTransaction::where('category_id', $category->id)
            ->where('approvedByAdmin_at', null)
            ->where('approvedByCashier_at', null)
            ->where('declinedByAdmin_at', null)
            ->get();

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

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        return view('admin.pages.transactions', compact('users', 'categoriesIsNull', 'currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));

    }

    public function decline(Request $request, $id)
    {

        $transaction = ItemsTransaction::findOrFail($id);

        $transaction->update([
            'message' => $request->message,
            'declinedByAdmin_at' => now(),
        ]);

        if ($transaction) {

            return redirect()->back()->with('success', 'Transaction has been successfully declined!');
        }


    }

    public function approve($id)
    {

        $transaction = ItemsTransaction::find($id);

        $transaction->update([
            'approvedByAdmin_at' => now(),
            'admin_id' => Auth::user()->id,
        ]);

        if ($transaction) {

            Notification::create([
                'icon' => Auth::user()->img,
                'category_id' => $transaction->category_id,
                'title' => 'Approved Reservation',
                'description' => Auth::user()->name . ' approved a new reservation, check it now!',
                'redirect_link' => 'reservations',
                'for' => 'cashier',
            ]);

            return redirect()->back()->with('success', 'Transaction has been successfuly approved!');
        }

    }

    public function filter(Request $request)
    {
        if ($request->status == 'pending') {
            $transactions = ItemsTransaction::where('declinedByAdmin_at', null)->where('approvedByCashier_at', null)->whereNot('approvedByAdmin_at', null)->where('category_id', $request->category)->get();

        } else if ($request->status == 'approved') {
            $transactions = ItemsTransaction::where('declinedByAdmin_at', null)->whereNot('approvedByCashier_at', null)->whereNot('approvedByAdmin_at', null)->where('category_id', $request->category)->get();

        } else if ($request->status == 'declined') {
            $transactions = ItemsTransaction::whereNot('declinedByAdmin_at', null)->where('category_id', $request->category)->get();

        }


        $current_user_name = Auth::user()->name;

        $category = $request->category;

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

        if ($roles->contains('superadmin')) {
            $categories = Category::all();

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

            $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();



        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');

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
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'category_id' => 'required|exists:categories,id',
            'rentee_name' => 'required|string|max:255',
            'rentee_contact_no' => 'required|string|max:255',
            'rentee_email' => 'required|string|email|max:255',
            'destination_id' => 'required|exists:destinations,id',
            'rent_date' => 'required|date',
            'rent_time' => 'required|date_format:H:i',
            'rent_return' => 'required|date|after_or_equal:rent_date',
            'rent_return_time' => 'required|date_format:H:i',
        ]);

        $existingTransaction = Transaction::where('item_id', $request->item_id)
            ->where('rentee_email', $request->rentee_email)
            ->where('status', 'pending')
            ->first();

        if ($existingTransaction) {
            return redirect()->back()->with('error', 'You already have a pending transaction for this item.');
        }

        try {
            Transaction::create([
                'item_id' => $validatedData['item_id'],
                'category_id' => $validatedData['category_id'],
                'rentee_name' => $validatedData['rentee_name'],
                'rentee_contact_no' => $validatedData['rentee_contact_no'],
                'rentee_email' => $validatedData['rentee_email'],
                'destination_id' => $validatedData['destination_id'],
                'rent_date' => $validatedData['rent_date'],
                'rent_time' => $validatedData['rent_time'],
                'rent_return' => $validatedData['rent_return'],
                'rent_return_time' => $validatedData['rent_return_time'],
                'status' => 'pending',
            ]);

            $isReadBy = [];

            Notification::create([
                'icon' => Auth::user()->img,
                'title' => 'New Transaction',
                'category_id' => $validatedData['category_id'],
                'description' => Auth::user()->name . ' added a new transaction.',
                'redirect_link' => 'transactions',
                'for' => 'all',
                'isReadBy' => $isReadBy,
            ]);

            return redirect()->back()->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            Log::error('Transaction creation error: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Error creating transaction. Please try again later.');
        }
    }

}
