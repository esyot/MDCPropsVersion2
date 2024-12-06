<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Destination;
use App\Models\Item;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Rentee;
use Illuminate\Http\Request;

class RenteeCartController extends Controller
{
    public function index($rentee)
    {

        $fetchedRentee = Rentee::where('code', $rentee)->first();


        if (!$fetchedRentee) {
            return redirect()->back()->with('error', 'Rentee not found.');
        }


        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();


        if (!$cart) {
            return redirect()->back()->with('cart', 'Cart not found, please add items to cart first.');
        }


        $propertyIds = json_decode($cart->properties);


        if (!is_array($propertyIds)) {
            $propertyIds = [];
        }


        $properties = Property::whereIn('id', $propertyIds)->get();

        return view('rentee.pages.cart', compact('fetchedRentee', 'properties', 'rentee'));
    }


    public function addToCart($rentee, $property)
    {
        $user = Rentee::where('code', $rentee)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User does not exist.');
        }

        $cart = Cart::where('rentee_id', $user->id)->first();
        $properties = $cart ? json_decode($cart->properties, true) : [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding cart properties.');
        }

        $properties[] = $property;

        $cart ? $cart->update(['properties' => json_encode($properties)]) :
            Cart::create(['rentee_id' => $user->id, 'properties' => json_encode([$property])]);

        return redirect()->back()->with('success', 'A Property added to cart successfully!');
    }


    public function checkout(Request $request, $rentee)
    {

        $selectedProperties = $request->input('properties');

        $properties = Property::whereIn('id', $selectedProperties)->get();

        $destinations = Destination::all();

        $propertiesHasRecord = PropertyReservation::whereIn('property_id', $selectedProperties)
            ->whereNull('returned_at')
            ->whereNull('canceledByRentee_at')
            ->whereNotNUll('approvedByAdmin_at')
            ->whereNotNUll('approvedByCashier_at')
            ->get();


        $unavailableDateRanges = $propertiesHasRecord->map(function ($reservation) {
            return [
                'start' => $reservation->date_start,
                'end' => $reservation->date_end,
            ];
        });



        $page_title = 'Checkout';
        return view('rentee.pages.checkout', compact(
            'rentee',
            'properties',
            'destinations',
            'page_title',
            'unavailableDateRanges'
        ));

    }


    public function removePropertyFromCart($id, $rentee, $properties)
    {
        if ($properties == '[]') {
            $renteePerson = Rentee::where('code', $rentee)->first();

            $cart = Cart::where('rentee_id', $renteePerson->id)->first();

            if (!$cart) {
                return redirect()->back()->with('error', 'Cart not found.');
            }

            $properties = json_decode($cart->properties, true);


            if (is_array($properties) && ($key = array_search($id, $properties)) !== false) {
                unset($properties[$key]);
            }

            $cart->properties = json_encode(array_values($properties));
            $cart->save();

            return redirect()->back()->with('success', 'Item has been removed from your cart');


        } else {

            $cartedProperties = json_decode($properties, true);


            $cartedProperties = array_filter($cartedProperties, function ($property) use ($id) {
                return $property['id'] != $id;
            });


            $cartedProperties = array_values($cartedProperties);

            $ids = array_column($cartedProperties, 'id');


            $properties = Property::whereIn('id', $ids)->get();

            if (count($properties) == 0) {
                return redirect()->route('cart', ['rentee' => $rentee])->with('error', 'No Properties selected');
            }

            $destinations = Destination::all();

            $page_title = 'Checkout';


            $propertiesHasRecord = PropertyReservation::whereIn('property_id', $ids)->get();

            $unavailableDateRanges = $propertiesHasRecord->map(function ($reservation) {
                return [
                    'start' => $reservation->date_start,
                    'end' => $reservation->date_end,
                ];
            });

            return view('rentee.pages.checkout', compact(
                'rentee',
                'properties',
                'destinations',
                'page_title',
                'unavailableDateRanges'
            ));


        }



    }


}
