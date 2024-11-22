@foreach ($reservations as $reservation)
    @php
        if ($reservation->created_at) {
            $reservationTime = $reservation->created_at instanceof \Carbon\Carbon
                ? $reservation->created_at
                : \Carbon\Carbon::parse($reservation->created_at);

            $currentTime = \Carbon\Carbon::now();

            $minutesAgo = $reservationTime->diffInMinutes($currentTime);


            if ($minutesAgo < 1) {
                $timeAgo = 'just now';
            } else {
                $timeAgo = $reservationTime->diffForHumans($currentTime, [
                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ]);
            }
        }

    @endphp


    <li hx-get="{{ route('cashier.reservation-details', ['tracking_code' => $reservation->tracking_code]) }}"
        hx-trigger="click" hx-swap="innerHTML" hx-target="#reservation-details"
        class="cursor-pointer p-2 m-2 bg-gray-100 hover:bg-gray-200 shadow-md" title="Click for preview">
        <div class="flex space-x-2">
            <h1 class="font-medium">Tracking Code:</h1>
            <span>

                {{$reservation->tracking_code}}
            </span>
        </div>
        <div class="flex space-x-2">
            <h1 class="font-medium">
                Rentee:
            </h1>
            <span>
                {{$reservation->rentee->name}}


        </div>
        <span class="text-red-500">
            {{$timeAgo}}
        </span>
    </li>
@endforeach


@if(count($reservations) == 0)

    <div class="flex justify-center items-center">
        <span>No results found.</span>
    </div>

@endif