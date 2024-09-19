<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $currentDate = now();
        $default = 1;

        $page_title = 'Dashboard';


        $transactions = Transaction::where('category_id', $default)->get();
        $categories = Category::orderBy('id')->get();
        $items = Item::where('category_id', $default)->get();

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


        $daysWithRecords = $transactions->map(fn($transaction) => Carbon::parse($transaction->rent_date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $setting = Setting::find(1);

        $categories_admin = Category::where('approval_level', 1)->get();
        $categories_staff = Category::where('approval_level', 2)->get();

        $roles = Auth::user()->getRoleNames();

        $currentCategory = null;

        if ($roles->contains('admin')) {
            $currentCategory = Category::where('approval_level', 1)->first();
        } elseif ($roles->contains('staff')) {
            $currentCategory = Category::where('approval_level', 2)->first();
        }

        return view('pages.dashboard', compact('currentCategory', 'roles', 'categories_admin', 'categories_staff', 'setting', 'current_user_name', 'contacts', 'unreadMessages', 'page_title', 'unreadNotifications', 'notifications', 'items', 'categories', 'currentDate', 'transactions', 'daysWithRecords'));



    }

    public function dateView($date)
    {
        $transactions = Transaction::where('rent_date', $date)->get();

        $setting = Setting::find(1);

        return view('pages.partials.date-view', compact('setting', 'transactions', 'date'));
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

        $items = Item::where('category_id', $category)->get();

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



        return view('pages.dashboard', compact('roles', 'categories_admin', 'categories_staff', 'setting', 'contacts', 'unreadMessages', 'page_title', 'notifications', 'unreadNotifications', 'items', 'currentCategory', 'categories', 'currentDate', 'transactions', 'daysWithRecords'));
    }

    public function transactionAdd(Request $request)
    {
        $current_user_name = Auth::user()->name;

        $validatedData = $request->validate([
            'item_id' => 'required',
            'category_id' => 'required',
            'rentee_name' => 'required|string|max:255',
            'rentee_contact_no' => 'required|string|max:255',
            'rentee_email' => 'required|string|email|max:255',
            'rent_date' => 'required|date',
            'rent_time' => 'required|date_format:H:i',
            'rent_return' => 'required|date',
            'rent_return_time' => 'required|date_format:H:i',
        ]);

        try {
            Transaction::create(array_merge($validatedData, ['status' => 'pending']));

            Notification::create([
                'icon' => "https://cdn-icons-png.flaticon.com/512/9187/9187604.png",
                'title' => "New Transaction",
                'description' => $current_user_name . " added a new transaction, check it now.",
                'redirect_link' => "transactions"
            ]);

            return redirect()->back()->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating transaction: ' . $e->getMessage());
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

        $items = Item::where('category_id', $category)->get();

        $setting = Setting::find(1);

        $categories_admin = Category::where('approval_level', 1)->get();
        $categories_staff = Category::where('approval_level', 2)->get();

        $roles = Auth::user()->getRoleNames();

        return view('pages.partials.calendar', compact('roles', 'categories_admin', 'categories_staff', 'setting', 'contacts', 'current_user_name', 'unreadMessages', 'page_title', 'notifications', 'unreadNotifications', 'items', 'currentCategory', 'categories', 'currentDate', 'transactions', 'daysWithRecords'));
    }
}
