<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Notification;
use App\Models\Rentee;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class RenteeReservationController extends Controller
{
    public function reservationAdd(Request $request, $rentee)
    {

        $renteeData = $request->validate([

            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',

        ]);


        $fetchedRentee = Rentee::where('code', $rentee)->first();

        if (!$fetchedRentee) {
            return response()->json(['message' => 'Rentee not found'], 404);
        }


        $reservation = DB::transaction(function () use ($fetchedRentee, $renteeData, $request) {

            $fetchedRentee->update($renteeData);

            $validatedData = $request->validate([
                'properties.*.property_id' => 'required|exists:properties,id',
                'properties.*.quantity' => 'required|integer|min:1',
                'destination' => 'required',
                'date_start' => 'required|date',
                'time_start' => 'required|date_format:H:i',
                'date_end' => 'required|date',
                'time_end' => 'required|date_format:H:i',
                'purpose' => 'required|string',
                'reservation_type' => 'required|string',
            ]);


            $trackingCode = now()->format('Ymd') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);

            $reservation = Reservation::create([
                'rentee_id' => $fetchedRentee->id,
                'tracking_code' => $trackingCode,
                'reservation_type' => $validatedData['reservation_type'],
                'purpose' => $validatedData['purpose']

            ]);


            foreach ($validatedData['properties'] as $data) {

                $property = Property::find($data['property_id']);


                if (!$property) {
                    throw new \Exception("Property not found: " . $data['property_id']);
                }

                $destination = Destination::where('municipality', 'LIKE', '%' . $validatedData['destination'] . '%')->first();

                PropertyReservation::create([
                    'reservation_id' => $reservation->id,
                    'destination_id' => $destination->id,
                    'category_id' => $property->category_id,
                    'property_id' => $data['property_id'],
                    'date_start' => $validatedData['date_start'],
                    'time_start' => $validatedData['time_start'],
                    'date_end' => $validatedData['date_end'],
                    'time_end' => $validatedData['time_end'],
                    'qty' => $data['quantity']
                ]);


                Notification::create([
                    'icon' => 'user.png',
                    'user_id' => 1,
                    'rentee_id' => $fetchedRentee->id,
                    'title' => 'Added new reservation',
                    'description' => 'A rentee requested a new reservation, check it now.',
                    'redirect_link' => 'reservations',
                    'for' => 'superadmin|admin',
                ]);
            }

            return $reservation;
        });

        return redirect()->route('rentee.reservation-complete', compact('reservation'));
    }


    public function reservationComplete($reservation)
    {

        session()->forget('rentee');

        return redirect()->route('rentee.welcome', compact('reservation'))->with('success', 'Reservation has been submitted successfully.');
    }

    public function reservationCancel($tracking_code)
    {
        $reservation = Reservation::where('tracking_code', $tracking_code)->first();

        $reservation->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);

        PropertyReservation::where('reservation_id', $reservation->id)->update([
            'canceledByRentee_at' => now()
        ]);

        return redirect()->back()->with('success', 'Reservation has been canceled successfully!');
    }
}
