@foreach ($transactions as $transaction)

    <div class="flex flex-col justify-start bg-white p-2 space-y-2">
        <div class="flex space-x-2">
            <p>Tracking code: </p>
            <span>
                {{ $transaction->tracking_code}}

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
            <p>Status: </p> <span>{{ $transaction->status }}</span>

        </div>
        @php
            $formattedDate = \Carbon\Carbon::parse($transaction->created_at)->format('l, F j, Y h:i A');


        @endphp
        <div class="flex space-x-2">
            <p>Transaction Date: </p> <span>{{ $formattedDate }}</span>
        </div>
        <div>
            <h1>Reserved Items:</h1>
        </div>
        @foreach ($items as $item)

            <div class="flex space-x-2">
                {{ $item->item->name }}
            </div>

        @endforeach

    </div>



@endforeach


@if(count($transactions) == 0)
    <div class="flex bg-white p-2 space-x-2">
        <span>No results found.</span>
    </div>
@endif