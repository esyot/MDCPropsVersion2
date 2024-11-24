@extends('admin.layouts.header')
@section('content')

<div id="dashboard" class="h-full w-full">
    @if (session('reservation') != null)

        <script src="{{ asset('asset/dist/qrious.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var reservation = @json(session('reservation')); // Retrieve reservation data from session
                var trackingCode = reservation.tracking_code; // Access tracking code from the session data
                var qr = new QRious({
                    element: document.getElementById('canvas'),
                    value: trackingCode,
                    size: 300
                });
            });
        </script>

        <div id="QR" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white p-4 rounded mx-2">
                <div class="flex justify-center">
                    <h1 class="text-2xl text-center font-bold">Reservation has been successfully submitted!</h1>
                </div>

                <div class="flex mt-2 justify-center">
                    <canvas id="canvas" class=""></canvas>
                </div>

                <div class="flex space-x-2 p-2 justify-center">
                    <h1>Tracking Code: </h1>
                    <span class="font-bold">
                        {{ session('reservation')->tracking_code }} <!-- Access the tracking code from session -->
                    </span>
                </div>

                <div class="flex justify-center">

                </div>

                <div class="flex justify-center mt-2">
                    <button onclick="document.getElementById('QR').classList.add('hidden')"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Done</button>
                </div>
            </div>
        </div>
    @endif



    @if ($categoriesIsNull == false)
        @include('admin.partials.calendar')
    @else

        @include('admin.partials.errors.category-null-error')

    @endif

</div>
@endsection