<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function welcome()
    {

    }

    public function home()
    {

        return view('cashier.pages.index');

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
}
