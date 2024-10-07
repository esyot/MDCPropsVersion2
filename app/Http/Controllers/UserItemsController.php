<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Item;

class UserItemsController extends Controller
{
    public function index($category_id)
    {
        $items = Item::where('category_id', $category_id)->get();

        return view('user.pages.items', compact('items'));
    }

    public function itemUnAvailableDates($id)
    {
        $transactions = Transaction::where('item_id', $id)->get();

        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'No transactions found for this item'], 404);
        }

        $unavailableDates = $transactions->flatMap(function ($transaction) {
            return [
                \Carbon\Carbon::parse($transaction->rent_date)->toISOString(),
                \Carbon\Carbon::parse($transaction->rent_return)->toISOString(),
            ];
        })->unique()->values()->toArray();

        return response()->json($unavailableDates);
    }









}
