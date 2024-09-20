<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Message;
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
            ->map(function ($group) {
                return $group->first();
            })
            ->values();


        $currentStatus = 'pending';


        $categories_admin = Category::where('approval_level', 1)->get();
        $categories_staff = Category::where('approval_level', 2)->get();

        $roles = Auth::user()->getRoleNames();

        $currentCategory = null;

        if ($roles->contains('admin') && $categories != null) {
            $currentCategory = Category::where('approval_level', 1)->first();
        } elseif ($roles->contains('staff')) {
            $currentCategory = Category::where('approval_level', 2)->first();
        }

        $categoriesIsNull = true;
        if (count($categories) > 0) {
            $categoriesIsNull = false;

        }
        return view('pages.transactions', compact('categoriesIsNull', 'currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));

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
            ->map(function ($group) {
                return $group->first();
            })
            ->values();


        $currentCategory = Category::find($request->category);

        $currentStatus = $request->status;

        return view('pages.transactions', compact('currentStatus', 'contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));


    }
}
