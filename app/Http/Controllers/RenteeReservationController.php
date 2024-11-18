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
        // Validate rentee fields
        $renteeData = $request->validate([

            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',

        ]);

        // Fetch rentee
        $fetchedRentee = Rentee::where('code', $rentee)->first();

        if (!$fetchedRentee) {
            return response()->json(['message' => 'Rentee not found'], 404);
        }

        // Start transaction
        $reservation = DB::transaction(function () use ($fetchedRentee, $renteeData, $request) {
            // Update rentee data
            $fetchedRentee->update($renteeData);

            // Validate transaction fields
            $validatedData = $request->validate([
                'properties.*.property_id' => 'required|exists:properties,id',
                'properties.*.quantity' => 'required|integer|min:1',
                'destination' => 'required',
                'date_start' => 'required|date',
                'time_start' => 'required|date_format:H:i',
                'date_end' => 'required|date',
                'time_end' => 'required|date_format:H:i',
            ]);

            // Create transaction
            $trackingCode = now()->format('Ymd') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);

            $reservation = Reservation::create([
                'rentee_id' => $fetchedRentee->id,
                'tracking_code' => $trackingCode,
                'reservation_type' => 'rent'

            ]);

            // Process each item in the transaction
            foreach ($validatedData['properties'] as $data) {
                // Fetch item
                $property = Property::find($data['property_id']);

                // Check if item exists
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

                // Add notification for each item
                Notification::create([
                    'icon' => 'user.png',
                    'title' => 'Added new transaction',
                    'description' => 'A rentee requested a new reservation, check it now.',
                    'redirect_link' => 'reservations',
                    'category_id' => $property->category_id,
                ]);
            }

            return $reservation;
        });

        return redirect()->route('rentee.reservation-complete', compact('reservation'));
    }


    public function reservationComplete($reservation)
    {

        session()->forget('rentee');

        return redirect()->route('welcome', compact('reservation'))->with('success', 'Reservation has been submitted successfully.');
    }

    public function reservationCancel($tracking_code)
    {
        $reservation = Transaction::where('tracking_code', $tracking_code)->first();

        $reservation->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);

        ItemsTransaction::where('transaction_id', $reservation->id)->update([
            'canceledByRentee_at' => now()
        ]);

        return redirect()->back()->with('success', 'Reservation has been canceled successfully!');
    }
}
