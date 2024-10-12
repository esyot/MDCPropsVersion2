<?php

namespace App\Http\Controllers;

use App\Models\Rentee;
use Illuminate\Http\Request;

class RenteeController extends Controller
{
    public function create()
    {

        $new_rentee = Rentee::create([

        ]);

        $rentee = Rentee::find($new_rentee->id);

        return view('rentee.pages.home', compact('rentee'));
    }
}
