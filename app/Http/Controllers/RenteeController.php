<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rentee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RenteeController extends Controller
{
    public function create()
    {
        if (session()->has('rentee')) {
            $rentee = session('rentee');

        } else {
            try {
                $rentee = $this->createNewRentee();
                session(['rentee' => $rentee]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Could not create rentee: ' . $e->getMessage()]);


            }
        }
        return redirect()->route('home', ['rentee' => $rentee->code]);
    }


    private function createNewRentee()
    {
        $dateTime = Carbon::now()->format('Ymd');
        $randomString = Str::random(8);
        $code = $dateTime . '-' . $randomString;

        return Rentee::create(['code' => $code]);
    }

    public function cancelOrder($rentee)
    {
        session()->forget('rentee');

        Rentee::where('code', $rentee)->first()->delete();

        return redirect()->route('rentee.welcome')->with('success', 'Session canceled successfully.');
    }
}
