@extends('admin.layouts.header')
@section('content')

<style>
@media(orientation:portrait){
    #reser$reservations-header-content{

    }
}
</style>

@if ($categoriesIsNull == false)

    <div id="reser$reservations-header" class="flex items-center justify-between p-4 shadow-md">
        <div id="reser$reservations-header-content" class="flex items-center space-x-2 ">
            <form onchange="this.submit()" action="{{ route('admin.reservations-filter') }}" class="flex justify-around space-x-4"
                method="GET">
                @csrf

                <select title="Category" name="category"
                    class="shadow-inner block px-4 py-2 border border-gray-500 rounded cursor-pointer">

                    <option class="text-red-500" value="{{ $currentCategory->id }}">{{$currentCategory->title }}</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>

                <select title="Status" name="status" id="" class="shadow-inner block px-4 py-2 border border-gray-500 rounded cursor-pointer">
                    <option value="{{ $currentStatus }}" class="text-red-500">{{ ucfirst($currentStatus) }}</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="declined">Declined</option>
                    <option value="canceled">Canceled</option>
                </select>

            </form>
        </div>
    </div>

    <div id="main-content" class="flex-1 overflow-y-auto custom-scrollbar w-full h-full">
        <div class="flex p-4">
            @foreach($reservations as $reservation)

                <div title="Click to preview details"
                    class="mx-2 w-64 bg-white rounded-lg shadow-md cursor-pointer overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-90' : '' }}">
                    <div onclick="document.getElementById('reservation-{{$reservation->id}}').classList.remove('hidden')">


                        <img src="{{ asset('storage/images/categories/' . $currentCategory->folder_name . '/' . $reservation->property->img) }}"
                            alt="{{ $reservation->property->name }}" class="w-80 h-48 object-cover">

                    </div>
                    <div class="flex flex-col flex-wrap p-4 bg-blue-500 text-white">
                        <div class="flex flex-col justify-center">
                            <div class="flex justify-center text-xl font-semibold">{{ $reservation->property->name }}</div>
                            <div class="flex items-center">
                                <span class="font-medium">Rentee:</span>
                                <span class="ml-2 text-yellow-300">
                                    {{ $reservation->reservation->rentee->name }}
                                   

                                </span>
                            </div>
                            @php
    if ($reservation->created_at) {
        // Convert to Carbon instance if necessary
        $reservationTime = $reservation->created_at instanceof \Carbon\Carbon
            ? $reservation->created_at
            : \Carbon\Carbon::parse($reservation->created_at);

        // Get the current time
        $currentTime = \Carbon\Carbon::now();

        // Calculate the time difference in minutes
        $minutesAgo = $reservationTime->diffInMinutes($currentTime);

        // Determine the appropriate time ago string
        if ($minutesAgo < 1) {
            $timeAgo = 'just now';
        } else {
            $timeAgo = $reservationTime->diffForHumans($currentTime, [
                'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
            ]);
        }
    }
@endphp

<div class="flex justify-center items-center">
    <small>{{ $timeAgo }}</small>
</div>


                        </div>
                        <div class="flex justify-center space-x-2 mt-2">
                            @can('can approve reservations')
                            @if ($currentStatus == 'pending')
                            
                           
                            <button
                                    onclick="document.getElementById('delete-confirmation-{{$reservation->id}}').classList.remove('hidden')"
                                    class="shadow px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
                                    <i class="fas fa-times mr-2"></i> Decline
                                </button>
                              
                                    <button type=" button"
                                        onclick="document.getElementById('reservation-confirm-{{ $reservation->id }}').classList.remove('hidden')"
                                        class="shadow px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center">
                                        <i class="fas fa-check mr-2"></i> Approve
                                    </button>
                              
                                    @endif
                            @endcan
                        </div>
                    </div>
                </div>
                @include('admin.modals.reservation')
                @include('admin.modals.reservation-confirm')
            @endforeach
        </div>
    </div>

   @include('admin.modals.reservation-decline')

@else

    @include('admin.partials.errors.category-null-error')

@endif

@endsection