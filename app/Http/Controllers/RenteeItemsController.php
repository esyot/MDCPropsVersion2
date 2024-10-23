<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Item;

use App\Models\ItemsTransaction;
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
    public function itemUnavailableDates($id)
    {
        $item = Item::find($id);
        $transactions = ItemsTransaction::where('item_id', $id)
            ->whereNotNull('approvedByAdmin_at')
            ->whereNotNull('approvedByCashier_at')
            ->get();

        // Return an empty array if there are no transactions for the item
        if ($transactions->isEmpty()) {
            return response()->json([]);
        }

        $unavailableDates = [];
        $totalUnavailableQty = 0;

        foreach ($transactions as $transaction) {
            // Parse the rent dates
            $start = \Carbon\Carbon::parse($transaction->rent_date);
            $end = \Carbon\Carbon::parse($transaction->rent_return);

            // Generate all dates between start and end, inclusive
            while ($start->lte($end)) {
                $unavailableDates[] = $start->toISOString();
                $start->addDay(); // Move to the next day
            }

            // Accumulate the unavailable quantities
            $totalUnavailableQty += $transaction->qty;
        }

        // Remove duplicates and return unique unavailable dates
        $uniqueUnavailableDates = array_values(array_unique($unavailableDates));

        // Check if the total unavailable quantity is less than the item's quantity
        if ($totalUnavailableQty < $item->qty) {
            return response()->json(['message' => 'All dates are available']);
        }

        // Return the unique unavailable dates
        return response()->json($uniqueUnavailableDates);
    }







    public function renteeItemsFilter(Request $request, $category_id, $rentee)
    {
        $items = Item::where('name', 'LIKE', '%' . $request->search . '%')->where('category_id', $category_id)->get();

        return view('rentee.partials.item-single', compact('items', 'rentee'));
    }
}
