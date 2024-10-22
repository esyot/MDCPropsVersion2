<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RenteeTrackingController extends Controller
{
    public function index(Request $request)
    {
        if ($request) {
            $transactions = Transaction::where('tracking_code', $request->search_val)->get();
            $transaction = $transactions->first();

            if ($transaction) {
                $items = ItemsTransaction::where('transaction_id', $transaction->id)->get();
            } else {
                $items = collect();
            }

            return view('rentee.pages.tracking', compact('transactions', 'items'));
        }


        $transactions = ItemsTransaction::groupBy('transaction_id');
        return view('rentee.pages.tracking', compact('transactions'));
    }


    public function track(Request $request)
    {

        $transactions = Transaction::where('tracking_code', $request->search_val)->get();

        $items = ItemsTransaction::where('transaction_id', $transactions->first()->id)->get();

        return view('rentee.partials.transaction-single', compact('transactions', 'items'));

    }

    public function fetch(Request $request)
    {

        $transactions = Transaction::where('tracking_code', $request->search_val)->get();

        $items = ItemsTransaction::where('transaction_id', $transactions->first()->id)->get();
        return view('rentee.pages.tracking', compact('transactions', 'items'));

    }

}
