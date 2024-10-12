<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCustomerServiceController extends Controller
{
    public function index()
    {
        return view('user.pages.customer-service');
    }
}
