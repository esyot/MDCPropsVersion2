<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Destination;
use App\Models\Item;
use App\Models\Rentee;
use Illuminate\Http\Request;

class RenteeCartController extends Controller
{
    public function index($rentee)
    {

        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();


        if (!$fetchedRentee) {
            return redirect()->back()->with('error', 'Rentee not found.');
        }


        $cart = Cart::where('rentee_id', $fetchedRentee->id)->first();


        if (!$cart) {
            return redirect()->back()->with('cart', 'Cart not found, please add items to cart first.');
        }


        $itemIds = json_decode($cart->items);


        if (!is_array($itemIds)) {
            $itemIds = [];
        }


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
                'items' => json_encode([$item])
            ]);
        } else {

            $items = json_decode($cart->items, true);
            $items[] = $item;


            $cart->items = json_encode($items);
            $cart->save();
        }

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }


    public function checkout(Request $request, $rentee)
    {

        $selectedItems = $request->input('items');

        $items = Item::whereIn('id', $selectedItems)->get();

        $destinations = Destination::all();

        return view('rentee.pages.checkout', compact('rentee', 'items', 'destinations'));

    }


    public function removeItemInCart($id, $rentee)
    {
        $renteePerson = Rentee::where('rentee_code', $rentee)->first();



        $cart = Cart::where('rentee_id', $renteePerson->id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'Cart not found.');
        }
        $items = json_decode($cart->items, true);


        if (is_array($items) && ($key = array_search($id, $items)) !== false) {
            unset($items[$key]);
        }


        $cart->items = json_encode(array_values($items));
        $cart->save();

        return redirect()->back()->with('success', 'Item has been removed from your cart');
    }


}
