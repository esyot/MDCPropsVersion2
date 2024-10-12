<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RenteeCustomerServiceController extends Controller
{
    public function index()
    {
        return view('rentee.pages.customer-service');
    }
}
