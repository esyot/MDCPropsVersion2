<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RenteeCartController extends Controller
{
    public function index()
    {
        return view('rentee.pages.cart');
    }
}
