<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemsTransaction;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{

    public function welcome()
    {

    }

    public function home()
    {
        $reservations = Transaction::all();
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('cashier.pages.index', compact('setting', 'reservations'));

    }

    public function sessionStart()
    {
        session()->put('cashier', Auth::user()->id);

        return redirect()->route('cashier.home')->with('success', 'Welcome ' . Auth::user()->name . '!');
    }


    public function reservations()
    {
        $setting = Setting::where('user_id', Auth::user()->id)->first();


        $itemsTransactions = ItemsTransaction::whereNull('approvedByCashier_at')
            ->whereNotNull('approvedByAdmin_at')
            ->get();


        $reservationIds = $itemsTransactions->pluck('transaction_id');

        $reservations = Transaction::whereIn('id', $reservationIds)->get();

        $items = ItemsTransaction::whereIn('transaction_id', $reservations->pluck('id'))->get();


        return view('cashier.pages.reservations', compact('reservations', 'items', 'setting'));
    }

    public function reservationDetails($tracking_code)
    {

        $reservation = Transaction::where('tracking_code', $tracking_code)->first();

        $items = ItemsTransaction::where('transaction_id', $reservation->id)->get();

        return view('cashier.modals.reservation-details', compact('reservation', 'items'));


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
        ]);


        $itemIds = $request->input('itemsInArray');


        ItemsTransaction::whereIn('item_id', $itemIds)->update([
            'approvedByCashier_at' => now(),
        ]);

        Transaction::find($itemIds[0])->update(['approved_at' => now()]);

        return redirect()->back()->with('success', 'Reservation has been processed successfully.');
    }


}
