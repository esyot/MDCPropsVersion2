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
        return redirect()->route('home', ['rentee' => $rentee->rentee_code]);
    }


    private function createNewRentee()
    {
        $dateTime = Carbon::now()->format('Ymd');
        $randomString = Str::random(8);
        $code = $dateTime . '-' . $randomString;

        return Rentee::create(['rentee_code' => $code]);
    }

    public function cancelOrder($rentee)
    {
        session()->forget('rentee');

        Rentee::where('rentee_code', $rentee)->first()->delete();

        return redirect()->route('welcome')->with('success', 'Session finished successfully.');
    }
}
