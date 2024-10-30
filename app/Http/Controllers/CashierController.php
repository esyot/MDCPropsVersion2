<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
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

        return view('cashier.pages.index');

    }

    public function sessionStart()
    {
        session()->put('cashier', Auth::user()->id);

        return redirect()->route('cashier.home')->with('success', 'Welcome ' . Auth::user()->name . '!');
    }


    public function reservations()
    {
        $reservations = Transaction::all();

        $items = ItemsTransaction::whereIn('transaction_id', $reservations->pluck('id'))->get();
        return view('cashier.pages.reservations', compact('reservations', 'items'));
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

}
