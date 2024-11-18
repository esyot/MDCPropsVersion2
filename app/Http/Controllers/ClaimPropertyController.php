<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\ManagedCategory;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class ClaimPropertyController extends Controller
{
    public function index()
    {
        $page_title = "Claim Items";

        $current_user_id = Auth::user()->id;

        $items = Item::all();
        // Messages
        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessages = $messages->count();
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


        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $roles = Auth::user()->getRoleNames();

        $users = User::all();
        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

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


        } else if ($roles->contains(key: 'admin')) {
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
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }


        $transactions = Transaction::where('status', 'approved')
            ->whereNot('approved_at', null)
            ->get();


        return view('admin.pages.claim-items', compact(
            'transactions',
            'categories',
            'users',
            'currentCategory',
            'roles',
            'setting',
            'current_user_id',
            'contacts',
            'unreadMessages',
            'page_title',
            'unreadNotifications',
            'notifications',
            'items',
            'categoriesIsNull',
        ));

    }
    public function searchReservationForClaim(Request $request)
    {
        if ($request->search_value == null) {
            $transactions = Transaction::where('status', 'approved')->get();
            return view('admin.partials.items-for-claim', compact('transactions'));

        }

        $transactions = Transaction::where('tracking_code', 'LIKE', '%' . $request->search_value . '%')->where('status', 'in progress')->get();
        return view('admin.partials.items-for-claim', compact('transactions'));

    }

    public function reservedItemsToClaim($transaction_id)
    {


        $reservations = ItemsTransaction::where('transaction_id', $transaction_id)->get();

        return view('admin.modals.reserved-items-to-claim', compact(
            'reservations',
            'transaction_id'
        ));
    }

    public function reservedItemsClaimed($transaction_id)
    {

        $items = ItemsTransaction::where('transaction_id', $transaction_id)->update([
            'claimed_at' => now()
        ]);

        if ($items) {
            Transaction::find($transaction_id)->update([
                'status' => 'occupied'
            ]);
        }

        return redirect()->back()->with('success', 'Items has been successfully claimed!');
    }
}
