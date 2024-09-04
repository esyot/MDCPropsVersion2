<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Message;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $current_user_name = "Reinhard Esteban";

        $transactions = Transaction::where('category_id', 1)->get();

        $unreadNotifications = Notification::where('isRead', false)->get()->count();
        $notifications = Notification::orderBy('created_at', 'DESC')->get();

        $categories = Category::all();


        $page_title = 'Pending Transactions';

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





        $currentCategory = Category::find(1);






        return view('pages.transactions', compact('contacts', 'unreadMessages', 'setting', 'page_title', 'currentCategory', 'categories', 'transactions', 'unreadNotifications', 'notifications'));

    }

    public function decline($id)
    {

        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        if ($transaction) {

            return redirect()->back()->with('success', 'Transaction has been successfully declined!');
        }


    }
}
