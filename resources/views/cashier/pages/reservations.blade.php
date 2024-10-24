@extends('cashier.layouts.header')
@section('content')

<section>
    <nav class="flex items-center justify-between p-2 bg-blue-200">
        <h1 class="text-xl font-medium">Reservations</h1>
        <div class="flex space-x-2">


            <form action="" class="flex items-center space-x-1 bg-white rounded-full shadow-inner p-2">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Reservations" class="bg-transparent focus:outline-none">
            </form>
            <button class="py-2 px-3 bg-blue-500 text-white rounded">
                <i class="fas fa-qrcode fa-lg"></i>

            </button>
        </div>
    </nav>


    <div>
        <ul class="p-2">
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
                            class="p-2 m-2 bg-gray-100 hover:bg-gray-200 shadow-md" title="Click for preview">
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
                                    {{$reservation->rentee->first_name}}
                                    {{$reservation->rentee->middle_name[0]}}.
                                    {{$reservation->rentee->last_name}}
                                </span>


                            </div>
                            <span class="text-red-500">
                                {{$timeAgo}}
                            </span>
                        </li>
            @endforeach

        </ul>

    </div>

    <div id="reservation-details">

    </div>

</section>

@endsection