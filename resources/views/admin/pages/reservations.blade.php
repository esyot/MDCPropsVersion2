@extends('admin.layouts.header')
@section('content')

<style>
    /* Media Query for Portrait orientation */
    @media(orientation: portrait) {
        #main-content {
            height: 700px;
        }
    }
</style>

@if ($categoriesIsNull == false)

    <div id="reser$reservations-header" class="flex items-center justify-between p-4 shadow-md">
        <div id="reser$reservations-header-content" class="flex items-center space-x-2 ">
            <form onchange="this.submit()" action="{{ route('admin.reservations-filter') }}"
                class="flex justify-around space-x-4" method="GET">
                @csrf

                <select title="Category" name="category"
                    class="shadow-inner block px-4 py-2 border border-gray-500 rounded cursor-pointer">
                    <option class="text-red-500" value="{{ $currentCategory->id }}">{{$currentCategory->title }}</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>

                <select title="Status" name="status" id=""
                    class="shadow-inner block px-4 py-2 border border-gray-500 rounded cursor-pointer">
                    <option value="{{ $currentStatus }}" class="text-red-500">{{ ucfirst($currentStatus) }}</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="declined">Declined</option>
                    <option value="canceled">Canceled</option>
                </select>

            </form>
        </div>
    </div>

    <div id="main-content" class="overflow-y-auto custom-scrollbar h-[550px]">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 p-2">
            @foreach($reservations as $reservation)
                <div title="Click to preview details"
                    class="mx-2 bg-white rounded-lg shadow-md cursor-pointer overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-90' : '' }}">
                    <div onclick="document.getElementById('reservation-{{$reservation->id}}').classList.remove('hidden')">
                        <img src="{{ asset('storage/images/categories/' . $currentCategory->folder_name . '/' . $reservation->property->img) }}"
                            alt="{{ $reservation->property->name }}" class="w-full h-48 object-cover">
                    </div>
                    <div class="flex flex-col p-4 bg-blue-500 text-white">
                        <div class="flex flex-col justify-center">
                            <div class="text-xl font-semibold text-center">{{ $reservation->property->name }}</div>
                            <div class="flex items-center justify-center mt-2">
                                <span class="font-medium">Rentee:</span>
                                <span class="ml-2 text-yellow-300">
                                    {{ $reservation->reservation->rentee->name }}
                                </span>
                            </div>

                            @php
                                if ($reservation->created_at) {
                                    $reservationTime = $reservation->created_at instanceof \Carbon\Carbon
                                        ? $reservation->created_at
                                        : \Carbon\Carbon::parse($reservation->created_at);
                                    $currentTime = \Carbon\Carbon::now();
                                    $minutesAgo = $reservationTime->diffInMinutes($currentTime);
                                    $timeAgo = $minutesAgo < 1 ? 'just now' : $reservationTime->diffForHumans($currentTime, [
                                        'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                    ]);
                                }
                            @endphp

                            <div class="flex justify-center items-center mt-2">
                                <small>{{ $timeAgo }}</small>
                            </div>
                        </div>

                        <div class="flex justify-center space-x-2 mt-4">
                            @can('can approve reservations')
                                @if ($currentStatus == 'pending')
                                    <button
                                        onclick="document.getElementById('delete-confirmation-{{$reservation->id}}').classList.remove('hidden')"
                                        class="shadow px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
                                        <i class="fas fa-times mr-2"></i> Decline
                                    </button>

                                    <button type="button"
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