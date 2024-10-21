<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Item;

use App\Models\Rentee;
use App\Models\Transaction;
use Illuminate\Http\Request;
class RenteeItemsController extends Controller
{
    public function index($category_id, $rentee)
    {

        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();


        if (!$fetchedRentee) {
            return redirect()->back()->with('error', 'Rentee not found.');
        }


        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();


        $cartedItems = 0;


        if ($cart) {
            $itemsInCart = json_decode($cart->items, true); // Decode the JSON to an array
            $cartedItems = is_array($itemsInCart) ? count($itemsInCart) : 0; // Count if it's an array

        }


        $items = Item::where('category_id', $category_id)->get();

        return view('rentee.pages.items', compact('items', 'category_id', 'rentee', 'cartedItems'));
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

    public function renteeItemsFilter(Request $request, $category_id, $rentee)
    {
        $items = Item::where('name', 'LIKE', '%' . $request->search . '%')->where('category_id', $category_id)->get();

        return view('rentee.partials.item-single', compact('items', 'rentee'));
    }
}
