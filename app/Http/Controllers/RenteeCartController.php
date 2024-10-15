<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Rentee;
use Illuminate\Http\Request;

class RenteeCartController extends Controller
{
    public function index($rentee)
    {
        // Fetch the rentee by code
        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();

        // Check if the rentee exists
        if (!$fetchedRentee) {
            return redirect()->back()->with('error', 'Rentee not found.');
        }

        // Fetch the cart for the rentee
        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();

        // Check if the cart exists
        if (!$cart) {
            return redirect()->back()->with('error', 'Cart not found.');
        }

        // Decode the JSON array of item IDs
        $itemIds = json_decode($cart->items);

        // Ensure itemIds is an array
        if (!is_array($itemIds)) {
            $itemIds = [];
        }

        // Fetch items based on the decoded IDs
        $items = Item::whereIn('id', $itemIds)->get();

        return view('rentee.pages.cart', compact('fetchedRentee', 'items', 'rentee'));
    }




    public function addToCart($rentee, $item)
    {
        $user = Rentee::where('rentee_code', $rentee)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User does not exist.');
        }

        $cart = Cart::where('rentee_id', $user->id)->first();

        if ($cart == null) {

            Cart::create([
                'rentee_id' => $user->id,
                'items' => json_encode([$item]) // Store as JSON
            ]);
        } else {
            // Decode the existing items, add the new item, and re-encode
            $items = json_decode($cart->items, true);
            $items[] = $item; // Add the new item to the array

            // Update the cart with the new items array
            $cart->items = json_encode($items);
            $cart->save();
        }

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }


}
