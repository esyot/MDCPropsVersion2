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
            ->get();

        $page_title = 'Properties';

        return view('rentee.pages.properties', compact(
            'properties',
            'category_id',
            'rentee',
            'cartedProperties',
            'page_title'
        ));
    }


    public function itemUnavailableDates($id)
    {
        $property = Property::find($id);

        $reservations = PropertyReservation::where('property_id', $id)
            ->whereNull('returned_at')
            ->whereNull('canceledByRentee_at')
            ->whereNotNUll('approvedByAdmin_at')
            ->whereNotNUll('approvedByCashier_at')
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json([]);
        }

        $unavailableDates = [];
        $totalUnavailableQty = 0;

        foreach ($reservations as $reservation) {

            $start = \Carbon\Carbon::parse($reservation->date_start);
            $end = \Carbon\Carbon::parse($reservation->date_end);


            while ($start->lte($end)) {
                $unavailableDates[] = $start->toISOString();
                $start->addDay();
            }


            $totalUnavailableQty += $reservation->qty;
        }

        $uniqueUnavailableDates = array_values(array_unique($unavailableDates));


        if ($totalUnavailableQty < $reservation->qty) {
            return response()->json(['message' => 'All dates are available']);
        }


        return response()->json($uniqueUnavailableDates);
    }

    public function renteeItemsFilter(Request $request, $category_id, $rentee)
    {
        $properties = Property::where('name', 'LIKE', '%' . $request->search . '%')->where('category_id', $category_id)->get();
        $page_title = 'Properties';
        return view('rentee.partials.property-single', compact(
            'properties',
            'page_title',
            'rentee'
        ));
    }
}
