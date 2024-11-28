<?php

namespace App\Http\Controllers;

use App\Models\ItemsTransaction;
use App\Models\PropertyReservation;
use App\Models\Reservation;
use Illuminate\Http\Request;

class RenteeTrackingController extends Controller
{
    public function index(Request $request)
    {
        if ($request) {
            $reservations = Reservation::where('tracking_code', $request->search_val)->get();
            $reservation = $reservations->first();

            if ($reservation) {
                $properties = PropertyReservation::where('reservation_id', $reservation->id)->get();
            } else {
                $properties = collect();
            }

            return view('rentee.pages.tracking', compact('reservations', 'properties'));
        }


        $reservations = PropertyReservation::groupBy('reservation_id');
        return view('rentee.pages.tracking', compact('reservations'));
    }


    public function track(Request $request)
    {

        $reservations = Reservation::where('tracking_code', $request->search_val)->get();

        $items = PropertyReservation::where('reservation_id', $reservations->first()->id)->get();

        return view('rentee.partials.reservation-single', compact('reservations', 'properties'));

    }

    public function fetch(Request $request)
    {

        $reservations = Reservation::where('tracking_code', $request->search_val)->get();

        $properties = PropertyReservation::where('reservation_id', $reservations->first()->id)->get();
        return view('rentee.pages.tracking', compact('reservations', 'properties'));

    }

}
