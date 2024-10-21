<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RenteeTrackingController extends Controller
{
    public function index()
    {
        return view('rentee.pages.tracking');
    }
}
