<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class UserItemsController extends Controller
{
    public function index($category_id)
    {
        $items = Item::where('category_id', $category_id)->get();

        return view('user.pages.items', compact('items'));
    }
}
