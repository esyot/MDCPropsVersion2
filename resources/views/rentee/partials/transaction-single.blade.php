@foreach ($transactions as $transaction)

    <div class="flex flex-col justify-start bg-white p-2 space-y-2 w-full">
        <div class="flex space-x-2">
            <p>Tracking code: </p>
            <span>
                {{ $transaction->tracking_code }}
            </span>
        </div>
        <div class="flex space-x-2">
            <p>Rentee: </p>
            <span>
                {{ $transaction->rentee->first_name }}
                {{ $transaction->rentee->last_name }}
            </span>
        </div>

        <div class="flex space-x-2">
            <p>Status: </p>
            @if ($transaction->approved_at != null && $transaction->status == 'pending')
                <div class="flex items-center space-x-2">
                    <span class="text-green-500">Approved</span>
                    <i class="fas fa-check-circle text-green-500"></i>

                </div>
            @elseif ($transaction->approved_at == null && $transaction->status == 'pending')
                <div class="flex items-center space-x-2">
                    <span class="text-orange-500">Pending</span>
                    <i class="fa-solid fa-hourglass-start text-orange-500"></i>

                </div>
            @elseif ($transaction->status == 'in progress')
                <div class="flex items-center space-x-2">
                    <span class="text-green-500">Occuppied</span>
                    <i class="fa-solid fa-business-time text-green-500"></i>

                </div>

            @endif
        </div>
        @php
            $formattedTransactionDate = \Carbon\Carbon::parse($transaction->created_at)->format('l, F j, Y h:i A');
        @endphp
        <div class="flex space-x-2">
            <p>Transaction Date: </p> <span>{{ $formattedTransactionDate }}</span>
        </div>

        <div>
            <h1>Requested Items:</h1>
        </div>


        @foreach ($items as $item)
            @php
                $formattedItemRentDate = \Carbon\Carbon::parse($item->rent_date)->format('F j, Y');
                $formattedItemRentReturnDate = \Carbon\Carbon::parse($item->rent_return)->format('F j, Y');



            @endphp
            @php
                $formattedRentTime = \Carbon\Carbon::parse($transaction->rent_time)->format('h:i A');
                $formattedRentReturnTime = \Carbon\Carbon::parse($transaction->rent_return_time)->format('h:i A');
            @endphp
            <div class="flex flex-col border border-gray-300 p-2 space-x-6 justify-between">
                Reserve {{ $item->item->name }}
                for this {{$formattedItemRentDate}} {{$formattedRentTime}} to {{$formattedItemRentReturnDate}}
                {{$formattedRentReturnTime}}.


                @if ($item->approvedByAdmin_at != null && $item->approvedByCashier_at != null)
                    <span>

                        <span class="text-green-500">Approved</span>
                        <i class="fas fa-check-circle text-green-500"></i>
                    </span>
                @elseif ($item->approvedByAdmin_at != null && $item->approvedByCashier_at == null)
                    <span>

                        <span class="text-orange-500">Waiting for payment</span>
                        <i class="fa-solid fa-credit-card text-orange-500"></i>
                    </span>
                @elseif ($item->approvedByAdmin_at == null && $item->declinedByAdmin_at == null)
                    <span>

                        <span class="text-orange-500">Pending admin approval</span>
                        <i class="fa-solid fa-hourglass-start text-orange-500"></i>
                    </span>
                @elseif ($item->declinedByAdmin_at != null)
                    <div class="flex items-center space-x-1">
                        <h1>Status:</h1>
                        <div class="flex items-center">
                            <span class="text-red-500">Declined</span>
                            <i class="fa-solid fa-ban text-red-500"></i>
                        </div>
                        <div class="flex space-x-1">
                            <h1>Message:</h1>
                            <span>{{$item->message}}</span>
                        </div>


                    </div>


                @endif



            </div>
        @endforeach
    </div>
@endforeach

@if (count($transactions) == 0)
    <div class="flex justify-center">
        <h1>
            No match found!
        </h1>
    </div>
@endif