@extends('cashier.layouts.header')
@section('content')

<section class="m-2 bg-gray-100 rounded">
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">Property Name</th>
                <th class="p-2 text-left">Rent Date</th>
                <th class="p-2 text-left">Return Date</th>
                <th class="p-2 text-left">Rentee</th>
                <th class="p-2 text-left">Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr class="hover:bg-gray-100 border-b cursor-pointer">
                    <td class="p-2">
                        {{$transaction->property->name}}
                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->rent_date))->format('F j, Y') }} at
                        {{ (new DateTime($transaction->rent_time))->format('g:i A') }}
                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->rent_return))->format('F j, Y') }} at
                        {{ (new DateTime($transaction->rent_return_time))->format('g:i A') }}
                    </td>
                    <td class="p-2">
                        {{$transaction->reservation->rentee->name}}

                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->created_at))->format('F j, Y \a\t g:i A') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection