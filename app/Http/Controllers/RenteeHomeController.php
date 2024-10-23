<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
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

        // Fetch the rentee by code
        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();


        // Fetch the cart for the rentee
        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();

        // Check if the cart exists
        if (!$cart) {

            $cartedItems = 0;

            return view('rentee.pages.index', compact('cartedItems', 'rentee', 'categories'));

        } else {

            // Decode the JSON array of item IDs
            $itemIds = json_decode($cart->items);

            // Ensure itemIds is an array
            if (!is_array($itemIds)) {
                $itemIds = [];
            }

            // Fetch items based on the decoded IDs
            $cartedItems = Item::whereIn('id', $itemIds)->get()->count();

            return view('rentee.pages.index', compact('cartedItems', 'rentee', 'categories'));

        }

    }

    public function welcome(Request $request)
    {

        if ($request) {

            $transaction = Transaction::find($request->transaction);


            return view('rentee.pages.welcome', compact('transaction'));


        }

        return view('rentee.pages.welcome');
    }

    public function backToHome($rentee)
    {
        $categories = Category::all();

        // Fetch the rentee by code
        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();


        // Fetch the cart for the rentee
        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();

        // Check if the cart exists
        if (!$cart) {

            $cartedItems = 0;

            return view('rentee.pages.index', compact('cartedItems', 'rentee', 'categories'));

        } else {

            // Decode the JSON array of item IDs
            $itemIds = json_decode($cart->items);

            // Ensure itemIds is an array
            if (!is_array($itemIds)) {
                $itemIds = [];
            }

            // Fetch items based on the decoded IDs
            $cartedItems = Item::whereIn('id', $itemIds)->get()->count();

            return view('rentee.pages.index', compact('cartedItems', 'rentee', 'categories'));


        }
    }


}
