<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Item;

class RenteeItemsController extends Controller
{
    public function index($category_id)
    {
        $items = Item::where('category_id', $category_id)->get();

        return view('rentee.pages.items', compact('items', 'category_id'));
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

    public function filter(Request $request, $category_id)
    {
        $items = Item::where('name', 'LIKE', '%' . $request->search . '%')->where('category_id', $category_id)->get();

        return view('rentee.partials.item', compact('items'));
    }

}
