<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Models\Destination;
use App\Models\ManagedCategory;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $currentDate = now();
        $page_title = 'Dashboard';



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

        if ($roles->contains('staff')) {
            $notifications = Notification::where('for', 'staff')->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::where('for', 'staff')->where('isRead', false)->count();

        } else {
            $notifications = Notification::where('for', 'admin')->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::where('for', 'admin')->where('isRead', false)->count();


        }








        if ($roles->contains('staff')) {

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id'); // Get the category IDs
            $categories = Category::whereIn('id', $categoryIds)->get(); // Fetch the categories
            $currentCategory = $categories->first(); // Set the first category as the current category


        } else {
            $categories = Category::all();
            $currentCategory = $categories->first();
        }

        if ($currentCategory) {
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }

        // Safely get items only if currentCategory exists
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();

        // Transactions and records
        $daysWithRecords = Transaction::all()->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))->unique()->values()->toArray();

        // Users and destinations
        $users = User::where('name', '!=', $current_user_name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = Transaction::all();

        return view('admin.pages.dashboard', compact(
            'categories',
            'destinations',
            'users',
            'currentCategory',
            'roles',
            'setting',
            'current_user_name',
            'contacts',
            'unreadMessages',
            'page_title',
            'unreadNotifications',
            'notifications',
            'items',
            'currentDate',
            'daysWithRecords',
            'transactions',
            'categoriesIsNull'
        ));
    }


    public function dateView($date)
    {
        $transactions = Transaction::where('rent_date', $date)->get();

        $setting = Setting::find(1);

        return view('admin.partials.date-view', compact('setting', 'transactions', 'date'));
    }

    public function dateCustom(Request $request)
    {
        $page_title = "Dashboard";
        $current_user_name = Auth::user()->name;

        $year = $request->year;
        $month = $request->month;
        $category = $request->category;

        $currentDate = Carbon::create($year, $month, 25);

        $transactions = Transaction::where('category_id', $category)->get();

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $daysWithRecords = $transactions->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $items = Item::where('category_id', $category)->orderBy('name', 'ASC')->get();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();


        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        $categoriesIsNull = false;

        if ($roles->contains('staff')) {

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = Category::find($category);


        } else {
            $categories = Category::all();
            $currentCategory = Category::find($category);
            $categoriesIsNull = false;

        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        return view(
            'admin.pages.dashboard',
            compact(
                'destinations',
                'users',
                'categoriesIsNull',
                'roles',
                'categories',
                'setting',
                'contacts',
                'unreadMessages',
                'page_title',
                'notifications',
                'unreadNotifications',
                'items',
                'currentCategory',
                'categories',
                'currentDate',
                'transactions',
                'daysWithRecords'
            )
        );
    }

    public function transactionAdd(Request $request)
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
                'user_id' => Auth::user()->id,
                'icon' => Auth::user()->img,
                'title' => "New Transaction",
                'description' => "$currentUserName added a new transaction, check it now.",
                'redirect_link' => "transactions"
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

    public function calendarMove($action, $category, $year, $month)
    {
        //date
        $currentDate = Carbon::create($year, $month, 1);

        if ($action === 'left') {
            $currentDate->subMonth();
        } elseif ($action === 'right') {
            $currentDate->addMonth();
        } elseif ($action === 'today') {
            $currentDate = now();
        }
        $current_user_name = Auth::user()->name;
        $page_title = 'Dashboard';

        // Notifications
        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        // Messages
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = $messages->groupBy('sender_name')->map(fn($group) => $group->first())->values();

        // Settings and roles
        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();
        $categoriesIsNull = false;

        if ($roles->contains('staff')) {

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id'); // Get the category IDs
            $categories = Category::whereIn('id', $categoryIds)->get(); // Fetch the categories

            $currentCategory = Category::find($category);
            $categoriesIsNull = false;


        } else {
            $categories = Category::all();
            $currentCategory = Category::find($category);
            $categoriesIsNull = false;
        }

        // Safely get items only if currentCategory exists
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : collect();

        // Transactions and records
        $daysWithRecords = Transaction::all()->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))->unique()->values()->toArray();

        // Users and destinations
        $users = User::where('name', '!=', $current_user_name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        $transactions = Transaction::all();

        return view('admin.partials.calendar', compact(
            'categories',
            'destinations',
            'users',
            'currentCategory',
            'roles',
            'setting',
            'current_user_name',
            'contacts',
            'unreadMessages',
            'page_title',
            'unreadNotifications',
            'notifications',
            'items',
            'currentDate',
            'daysWithRecords',
            'transactions',
            'categoriesIsNull'
        ));
    }
}
