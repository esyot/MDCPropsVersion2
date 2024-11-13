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
    @include('admin.styles.dark-mode')

    @if (!Auth::user()->isPasswordChanged && $page_title != 'Profile')
        <script>
            const redirectUrl = '{{ route('profile') }}';
            window.location.href = redirectUrl;
        </script>
    @endif
    @hasrole('cashier')
    <script>
        window.location.href = "{{ route('cashier.home') }}"; 
    </script>
    @endhasrole

</head>
<style>
    .animation-open {
        animation-name: fadeIn, zoomIn;
        animation-duration: 400ms, 400ms;
        animation-timing-function: ease, ease;
        animation-fill-mode: forwards;
    }


    .animation-close {
        animation-name: fadeOut, zoomOut;
        animation-duration: 150ms, 150ms;
        animation-timing-function: ease, ease;
        animation-fill-mode: forwards;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }

    }

    @keyframes zoomIn {
        0% {
            transform: scale(0.1);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes zoomOut {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(0.9);
        }
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .placeholder-center::placeholder {

        text-align: center;
    }

    @media(orientation:landscape) {

        #footer {
            display: none;
        }

        #see-more-btn {
            display: none;
        }

        #footer-portrait {
            display: none;
        }

    }

    @media (orientation: portrait) {


        #sidebar {
            display: none;
        }

        #topbar-content {
            display: flex;
            justify-content: flex-end;
        }

        #notificationTitle {
            display: none;
        }

        #messageTitle {
            display: none;
        }

        #calendar-controls {
            display: none;
        }

        #custom-date-form {
            margin-right: 12px;
        }

        #footer-landscape {
            display: none;
        }


    }
</style>

<body class="font-verdana flex h-screen overflow-hidden bg-gray-100 text-gray-800">
    <!-- Components -->
    @include('admin.components.rightbar')
    @include('admin.components.leftbar')
    @include('admin.components.topbar')

    <!-- contents -->

    <div id="content" class="h-full overflow-hidden">
        @yield('content')
    </div>

    <!-- <footer id="" class="bg-white">
        All rights reserved &copy; 2024
    </footer> -->

    <footer id="footer-portrait" class="flex bg-blue-500 p-2 justify-center z-50 shadow-md">


        <div class="flex items-center space-x-8 overflow-x-auto transition-transform duration-300 ease-in-out">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                <i class="fa-solid fa-calendar-days text-white drop-shadow-lg"></i>
                <small class="text-white">Dashboard</small>
            </a>

            <a href="{{ route('items') }}" class="flex flex-col items-center">
                <i class="fa-solid fa-boxes-stacked text-white drop-shadow-lg"></i>
                <small class="text-white">Items</small>
            </a>
            <a href="{{ route('categories') }}" class="flex flex-col items-center">
                <i class="fa-solid fa-th-large  text-white drop-shadow-lg"></i>
                <small class="text-white">Categories</small>

            </a>
            <a href="{{ route('transactions') }}" class="flex flex-col items-center">
                <i class="fa-solid fa-business-time  text-white drop-shadow-lg"></i>
                <small class="text-white">Transactions</small>
            </a>


            <a href="{{ route('admin.claim-items') }}" class="flex flex-col items-center">
                <i class="fas fa-hands  text-white drop-shadow-lg"></i>
                <small class="text-white">Claim</small>
            </a>



            <a href="{{ route('admin.return-items') }}" class="flex flex-col items-center">
                <i class="fas fa-truck text-white drop-shadow-lg"></i>
                <small class="text-white">Return</small>
            </a>

            <a href="{{ route('users') }}" class="flex flex-col items-center">
                <i class="fa-solid fa-users-gear text-white drop-shadow-lg"></i>
                <small class="text-white">Users</small>
            </a>


            <a href="{{ route('admin.analytics-index') }}" class="flex flex-col items-center">
                <i class="fas fa-chart-line text-white drop-shadow-lg"></i>
                <small class="text-white">Analytics</small>
            </a>

        </div>

        </div>
    </footer>