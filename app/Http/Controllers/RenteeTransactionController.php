<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemsTransaction;
use App\Models\Notification;
use App\Models\Rentee;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class RenteeTransactionController extends Controller
{
    public function store(Request $request, $rentee)
    {
        // Validate rentee fields
        $renteeData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:15',
            'address_1' => 'nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ]);

        // Fetch rentee
        $fetchedRentee = Rentee::where('rentee_code', $rentee)->first();

        if (!$fetchedRentee) {
            return response()->json(['message' => 'Rentee not found'], 404);
        }

        // Start transaction
        $transaction = DB::transaction(function () use ($fetchedRentee, $renteeData, $request) {
            // Update rentee data
            $fetchedRentee->update($renteeData);

            // Validate transaction fields
            $validatedData = $request->validate([
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.destination' => 'required',
                'items.*.rent_date' => 'required|date',
                'items.*.rent_time' => 'required|date_format:H:i',
                'items.*.rent_return' => 'required|date',
                'items.*.rent_return_time' => 'required|date_format:H:i',
            ]);

            // Create transaction
            $trackingCode = now()->format('Ymd') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);

            $transaction = Transaction::create([
                'rentee_id' => $fetchedRentee->id,
                'tracking_code' => $trackingCode,
                'status' => 'pending',
            ]);

            // Process each item in the transaction
            foreach ($validatedData['items'] as $data) {
                // Fetch item
                $item = Item::find($data['item_id']);

                // Check if item exists
                if (!$item) {
                    throw new \Exception("Item not found: " . $data['item_id']);
                }

                // Create ItemsTransaction entry for each item
                ItemsTransaction::create([
                    'transaction_id' => $transaction->id,
                    'destination_id' => $data['destination'],
                    'category_id' => $item->category_id,
                    'item_id' => $data['item_id'],
                    'rent_date' => $data['rent_date'],
                    'rent_time' => $data['rent_time'],
                    'rent_return' => $data['rent_return'],
                    'rent_return_time' => $data['rent_return_time'],
                    'qty' => $data['quantity']
                ]);

                // Add notification for each item
                Notification::create([
                    'icon' => 'user.png',
                    'title' => 'Added new transaction',
                    'description' => 'A rentee added a new transaction, check it now.',
                    'redirect_link' => 'transactions',
                    'category_id' => $item->category_id,
                ]);
            }

            return $transaction; // Return the created transaction
        });

        return redirect()->route('transactionDone', compact('transaction'));
    }


    public function transactionDone($transaction)
    {

        session()->forget('rentee');

        return redirect()->route('welcome', compact('transaction'))->with('success', 'Transaction has been saved successfully, please wait');
    }
}
