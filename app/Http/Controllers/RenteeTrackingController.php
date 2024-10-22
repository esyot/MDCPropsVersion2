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


            return view('rentee.pages.tracking', compact('transactions'));

        }

        $transactions = ItemsTransaction::groupBy('transaction_id');
        return view('rentee.pages.tracking', compact('transactions'));
    }


    public function track(Request $request)
    {



        $transactions = Transaction::where('tracking_code', $request->search_val)->get();


        return view('rentee.partials.transaction-single', compact('transactions'));

    }

    public function fetch(Request $request)
    {

        $transactions = Transaction::where('tracking_code', $request->search_val)->get();


        return view('rentee.pages.tracking', compact('transactions'));

    }

}
