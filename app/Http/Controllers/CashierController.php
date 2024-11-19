<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemsTransaction;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PropertyReservation;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class CashierController extends Controller
{
    public function home()
    {
        $page_title = 'Home';
        $current_user_id = Auth::user()->id;

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



        $notifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->get();

        $unreadNotifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
            ->count();

        $reservationsPending = PropertyReservation::where('approvedByCashier_at', null)
            ->whereNot('approvedByAdmin_at', null)
            ->where('canceledByRentee_at', null)
            ->get()
            ->count();

        $transactionMade = PropertyReservation::where('cashier_id', Auth::user()->id)
            ->get()
            ->count();



        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('cashier.pages.index', compact('unreadNotifications', 'transactionMade', 'contacts', 'unreadMessages', 'setting', 'reservationsPending', 'notifications', 'page_title'));

    }

    public function sessionStart()
    {
        session()->put('cashier', Auth::user()->id);

        return redirect()->route('cashier.home')->with('success', 'Welcome ' . Auth::user()->name . '!');
    }


    public function reservations()
    {
        $page_title = 'Reservations';
        $current_user_id = Auth::user()->id;
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



        $notifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->get();

        $unreadNotifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
            ->count();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $propertyReservation = PropertyReservation::whereNot('approvedByAdmin_at', null)
            ->where('approvedByCashier_at', null)
            ->where('declinedByAdmin_at', null)
            ->where('canceledByRentee_at', null)
            ->get();

        $reservationIds = $propertyReservation->pluck('reservation_id');

        $reservations = Reservation::whereIn('id', $reservationIds)->get();

        $properties = PropertyReservation::whereIn('reservation_id', $reservations->pluck('id'))->get();

        return view('cashier.pages.reservations', compact('unreadNotifications', 'notifications', 'contacts', 'reservations', 'properties', 'setting'));
    }

    public function reservationDetails($tracking_code)
    {

        $reservation = Reservation::where('tracking_code', $tracking_code)->first();


        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found.');
        }


        $propertyReservations = PropertyReservation::where('reservation_id', $reservation->id)
            ->whereNull('declinedByAdmin_at')
            ->whereNotNull('approvedByAdmin_at')
            ->whereNull('canceledByRentee_at')
            ->whereNull('approvedByCashier_at')
            ->get();

        $startDateTime = Carbon::parse($propertyReservations->first()->date_start . ' ' . $propertyReservations->first()->time_start);
        $endDateTime = Carbon::parse($propertyReservations->first()->date_end . ' ' . $propertyReservations->first()->time_end);

        $hours = $startDateTime->diffInHours($endDateTime);
        $days = $startDateTime->diffInDays($endDateTime);


        return view('cashier.modals.reservation-details', compact(
            'reservation',
            'propertyReservations',
            'days',
            'hours',
        ));
    }
    public function search(Request $request)
    {
        if ($request->search_value == null) {
            $reservations = Transaction::all();

            $items = ItemsTransaction::whereIn('transaction_id', $reservations->pluck('id'))->get();
            return view('cashier.partials.reservations', compact('reservations', 'items'));

        }

        $reservations = Transaction::where('tracking_code', $request->search_value)->get();
        $items = ItemsTransaction::whereIn('transaction_id', $reservations->pluck('id'))->get();

        return view('cashier.partials.reservations', compact('reservations', 'items'));
    }

    public function payment(Request $request)
    {

        $request->validate([
            'itemsInArray' => 'required|array',
            'itemsInArray.*' => 'integer|exists:items,id',
            'reservation_id' => 'integer|required'

        ]);

        Transaction::where('id', $request->reservation_id)->update([
            'approved_at' => now(),
            'status' => 'approved'
        ]);

        $itemIds = $request->input('itemsInArray');


        ItemsTransaction::where('transaction_id', $request->reservation_id)
            ->whereIn('item_id', $itemIds)->update([
                    'approvedByCashier_at' => now(),
                    'cashier_id' => Auth::user()->id,
                ]);


        Notification::create([
            'icon' => Auth::user()->img,
            'title' => 'Approved Transaction',
            'description' => Auth::user()->name . ' approved a new transaction, check it now!',
            'redirect_link' => 'transactions',
            'for' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Reservation has been processed successfully.');
    }


    public function transactions()
    {
        $page_title = 'Transactions';
        $current_user_name = Auth::user()->name;
        // Messages
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();



        $notifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->get();

        $unreadNotifications = Notification::where('for', 'cashier')
            ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
            ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
            ->count();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $transactions = ItemsTransaction::where('cashier_id', Auth::user()->id)->get();

        return view('cashier.pages.transactions', compact('unreadNotifications', 'notifications', 'transactions', 'contacts', 'unreadMessages', 'setting', 'notifications', 'page_title'));

    }


    public function notificationsFilter($action)
    {

        if ($action == 'unread') {

            $notifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->where('for', 'cashier')
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->get();

            return view('cashier.partials.notification-list', compact('notifications'));
        }

        if ($action == 'all') {
            $notifications = Notification::where('for', 'cashier')
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->get();

            return view('cashier.partials.notification-list', compact('notifications'));
        }
    }


}
