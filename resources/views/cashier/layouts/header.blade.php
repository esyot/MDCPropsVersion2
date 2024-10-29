<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>

<body>
    <header class="flex items-center p-2 space-x-2 bg-gradient-to-r from-blue-500 to-blue-800 w-full shadow-md">

        <div class="shadow-md rounded-full">
            <img src="{{ asset('asset/logo/logo.png') }}" class="h-[50px] w-[50px]" alt="">

        </div>

        <nav class="flex items-center justify-between w-full">
            <ul class="flex space-x-4 text-white">
                <li class="hover:opacity-50 cursor-pointer">Home</li>
                <a href="{{ route('cashier.reservations') }}">
                    <li class="hover:opacity-50 cursor-pointer">Reservations</li>
                </a>
                <li class="hover:opacity-50 cursor-pointer">Transactions</li>
            </ul>
            <div class="hover:opacity-50">
                <img src="{{ asset('asset/photos/user.png') }}" class="w-[40px] h-[40px]" alt="">
            </div>
        </nav>


    </header>

    @yield('content')

   