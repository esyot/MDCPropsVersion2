<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">

    <title>Dashboard</title>
</head>

<body>
    @if (request('transaction') != null)

        <script src="{{ asset('asset/dist/qrious.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var trackingCode = '{{ $transaction->tracking_code }}';
                var qr = new QRious({
                    element: document.getElementById('canvas'),
                    value: trackingCode,
                    size: 300
                });
            });
        </script>


        <div id="QR" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white p-4 rounded">
                <div class="flex justify-center">
                    <h1 class="text-2xl font-bold">Your reservation has been successfully saved!</h1>
                </div>

                <div class="flex mt-2 justify-center ">
                    <canvas id="canvas" class=""></canvas>

                </div>
                <div class="flex space-x-2 justify-center">
                    <h1>Tracking Code: </h1>
                    <span class="font-bold">
                        {{ $transaction->tracking_code }}
                    </span>

                </div>

                <div class="flex flex-col mt-2 items-center space-x-1">

                    <small class="border border-red-500 p-2">Note: Please save this QR code; it will be used to track your
                        reservation on the tracking page <br> and for payment at the cashier once your reservation is
                        confirmed.</small>
                </div>
                <div class="flex justify-center mt-2">
                    <button onclick="document.getElementById('QR').classList.add('hidden')"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Done</button>
                </div>
            </div>
        </div>
    @endif
    <div title="Monitor Transaction"
        class="fixed right-0 z-50 p-2 transition-transform duration-300 hover:scale-110 ease-in-out">
        <a href="{{ route('tracking') }}"> <i class="fas fa-desktop fa-xl text-white shadow-md"></i></a>
    </div>
    <div id="welcome"
        class="flex fixed inset-0 bg-gradient-to-b from-blue-500 to-blue-900 justify-center items-center z-40">
        <div class="bg-white rounded shadow-2xl p-2">
            <header class="flex flex-col items-center">
                <div class="mt-2">
                    <img src="{{ asset('asset/logo/logo.png') }}"
                        class="p-1 border-4 border-blue-300 rounded-full shadow-md h-32" alt="">
                </div>
                <div class="flex p-2 flex-col justify-center items-center">
                    <h1 class="text-4xl font-bold text-blue-500">MDC PropRentals</h1>
                    <small>"Avail, Rent & Return."</small>
                </div>
            </header>
            <section class="flex p-2 justify-center">

            </section>

            <div class="text-green-600 text-center">
                <p>
                    @if (session()->has('rentee'))
                        <h1 class="text-2xl font-bold">Welcome back!</h1>
                    @else
                        <h1 class="text-2xl font-bold">Welcome!</h1>
                    @endif
                </p>
            </div>

            <footer class="flex justify-center p-2 mb-2">
                <a href="{{ route('getStarted') }}"
                    class="px-4 py-2 bg-blue-200 text-blue-800 rounded-lg hover:bg-blue-500 hover:text-blue-100 shadow">
                    {{ session()->has('rentee') ? 'Back to Home' : 'Get started' }}
                </a>
            </footer>
        </div>
    </div>
</body>

</html>