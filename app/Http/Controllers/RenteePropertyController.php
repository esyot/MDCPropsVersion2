<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Rentee;
use App\Models\Reservation;
use Illuminate\Http\Request;

class RenteePropertyController extends Controller
{
    public function index($category_id, $rentee)
    {

        $fetchedRentee = Rentee::where('code', $rentee)->first();


        if (!$fetchedRentee) {
            return redirect()->back()->with('error', 'Rentee not found.');
        }


        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();


        $cartedProperties = 0;


        if ($cart) {
            $propertiesInCart = json_decode($cart->properties, true); // Decode the JSON to an array
            $cartedProperties = is_array($propertiesInCart) ? count($propertiesInCart) : 0; // Count if it's an array

        }


        $properties = Property::where('category_id', $category_id)
            ->whereNot('price', 0.00)
            ->get();

        return view('rentee.pages.properties', compact('properties', 'category_id', 'rentee', 'cartedProperties'));
    }
    public function itemUnavailableDates($id)
    {
        $item = Item::find($id);
        $transactions = ItemsTransaction::where('item_id', $id)
            ->whereNotNull('approvedByAdmin_at')
            ->whereNotNull('approvedByCashier_at')
            ->where('returned_at', null)
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
