@extends('admin.layouts.header')
@section('content')

<div id="dashboard" class="h-full w-full">

    @if ($categoriesIsNull == false)


        @if (session()->has('tracking_code'))
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
                            {{ session('tracking_code') }}
                        </span>
                    </div>

                    <div class="flex justify-center">
                        <small class="font-bold">Note: <i class="font-normal text-xs">Please save this qr code.</i></small>
                    </div>

                    <div class="flex justify-center mt-2 space-x-2">

                        <button onclick="document.getElementById('QR').remove()"
                            class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Done</button>
                        <button onclick="printQR()" class="px-4 py-2 bg-green-500 text-green-100 hover:opacity-50 rounded">Print
                            QR Code</button>
                    </div>
                </div>
            </div>

            <script>

                const trackingCode = @json(session('tracking_code'));

                var qr = new QRious({
                    element: document.getElementById('canvas'),
                    value: trackingCode,
                    size: 300
                });

                var canvas = document.getElementById('canvas');
                var dataURL = canvas.toDataURL('image/png');
                var img = new Image();
                img.src = dataURL;

                function printQR() {


                    var printWindow = window.open('', '', 'width=600,height=600');
                    printWindow.document.write('<html><head><title>Print QR Code</title></head><body style="margin: 0; padding: 0; position: relative; width: 100%; height: 100%; overflow: hidden;">');


                    printWindow.document.write('<img src="' + dataURL + '" style=" width: 35%; height: auto;" />');
                    printWindow.document.write('<p style="">Tracking Code: ' + '{{ session("tracking_code") }}' + '</p>');

                    printWindow.document.write('</body></html>');

                    printWindow.document.close();


                    printWindow.print();

                }

            </script>
        @endif

        @include('admin.partials.calendar')
    @else

        @include('admin.partials.errors.category-null-error')

    @endif

</div>
@endsection