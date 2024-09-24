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

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        $categories_admin = Category::where('approval_level', 1)
            ->orWhere('approval_level', 3)
            ->orderBy('id')
            ->get();

        $categories_staff = Category::where('approval_level', 2)
            ->orWhere('approval_level', 3)
            ->orderBy('id')
            ->get();

        $currentCategory = null;
        $categoriesIsNull = true;

        if ($roles->contains('admin') && $categories_admin->isNotEmpty()) {
            $currentCategory = $categories_admin->first();
            $categoriesIsNull = false;
        } elseif ($roles->contains('staff') && $categories_staff->isNotEmpty()) {
            $currentCategory = $categories_staff->first();
            $categoriesIsNull = false;
        }

        $transactions = [];
        $items = [];
        $daysWithRecords = [];

        if ($currentCategory) {
            $transactions = Transaction::where('category_id', $currentCategory->id)->get();
            $items = Item::where('category_id', $currentCategory->id)->orderBy('name', 'ASC')->get();

            $daysWithRecords = $transactions->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))
                ->unique()
                ->values()
                ->toArray();
        }

        $users = User::whereNot('name', $current_user_name)->get();

        $destinations = Destination::orderBy('municipality', 'ASC')->get();


        return view('admin.pages.dashboard', compact('destinations', 'users', 'categoriesIsNull', 'currentCategory', 'roles', 'categories_admin', 'categories_staff', 'setting', 'current_user_name', 'contacts', 'unreadMessages', 'page_title', 'unreadNotifications', 'notifications', 'items', 'categories_admin', 'currentDate', 'transactions', 'daysWithRecords'));
    }


    public function dateView($date)
    {
        $transactions = Transaction::where('rent_date', $date)->get();

        $setting = Setting::find(1);


        return view('admin.pages.partials.date-view', compact('setting', 'transactions', 'date'));
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
        $categories = Category::orderBy('id')->get();
        $currentCategory = Category::find($category);

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

        $categories_admin = Category::where('approval_level', 1)->get();
        $categories_staff = Category::where('approval_level', 2)->get();

        $roles = Auth::user()->getRoleNames();

        $categoriesIsNull = true;
        if (count($categories) > 0) {
            $categoriesIsNull = false;

        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        $destinations = Destination::orderBy('municipality', 'ASC')->get();

        return view('admin.pages.dashboard', compact('destinations', 'users', 'categoriesIsNull', 'roles', 'categories_admin', 'categories_staff', 'setting', 'contacts', 'unreadMessages', 'page_title', 'notifications', 'unreadNotifications', 'items', 'currentCategory', 'categories', 'currentDate', 'transactions', 'daysWithRecords'));
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
                'icon' => "https://cdn-icons-png.flaticon.com/512/9187/9187604.png",
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
        $page_title = 'Dashboard';
        $current_user_name = Auth::user()->name;

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $currentDate = Carbon::create($year, $month, 1);

        if ($action === 'left') {
            $currentDate->subMonth();
        } elseif ($action === 'right') {
            $currentDate->addMonth();
        } elseif ($action === 'today') {
            $currentDate = now();
        }

        $transactions = Transaction::where('category_id', $category)->get();
        $categories = Category::orderBy('id')->get();
        $currentCategory = Category::find($category);

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $daysWithRecords = $transactions->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $items = Item::where('category_id', $category)->orderBy('name', 'ASC')->get();

        $setting = Setting::find(1);

        $categories_admin = Category::where('approval_level', 1)->get();
        $categories_staff = Category::where('approval_level', 2)->get();

        $roles = Auth::user()->getRoleNames();

        return view('admin.pages.partials.calendar', compact('roles', 'categories_admin', 'categories_staff', 'setting', 'contacts', 'current_user_name', 'unreadMessages', 'page_title', 'notifications', 'unreadNotifications', 'items', 'currentCategory', 'categories', 'currentDate', 'transactions', 'daysWithRecords'));
    }
}
