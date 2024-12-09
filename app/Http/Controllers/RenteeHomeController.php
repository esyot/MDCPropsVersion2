<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Reservation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Rentee;

class RenteeHomeController extends Controller
{

    public function home($rentee)
    {
        $categories = Category::all();

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


        return view('rentee.pages.index', compact('cartedProperties', 'rentee', 'categories'));


    }

    public function welcome(Request $request)
    {

        if ($request->reservation) {

            $reservation = Reservation::find($request->reservation);

            $properties = PropertyReservation::where('reservation_id', $reservation->id)->get();

            return view('rentee.pages.welcome', compact('reservation', 'properties'));

        }

        return view('rentee.pages.welcome');
    }

    public function backToHome($rentee)
    {
        $categories = Category::all();


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

        return view('rentee.pages.index', compact('cartedProperties', 'rentee', 'categories'));


    }
}



