<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function index()
    {
        return view('user.pages.cart');
    }
}
