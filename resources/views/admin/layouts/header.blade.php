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
    @include('admin.styles.header')

    @if (!Auth::user()->isPasswordChanged && $page_title != 'Profile')
        <script>
            const redirectUrl = '{{ route('profile') }}';
            window.location.href = redirectUrl;
        </script>
    @endif

</head>
<style>
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
    @yield('content')




    <footer id="footer-landscape" class="flex p-2 justify-center">
        All rights reserved &copy; 2024
    </footer>

    <footer id="footer-portrait" class="flex bg-blue-500 p-2 justify-center z-50 shadow-md">


        <div class="items-center flex p-2 space-x-8">
            <a href="{{ route('categories') }}">
                <i class="fa-solid fa-th-large text-white drop-shadow-lg"></i>

            </a>
            <a href="{{ route('items') }}">
                <i class="fa-solid fa-boxes-stacked text-white drop-shadow-lg"></i>
            </a>


            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home text-white fa-2xl drop-shadow-lg"></i>
            </a>


            <a href="{{ route('transactions') }}">
                <i class="fa-solid fa-business-time text-white drop-shadow-lg"></i>
            </a>
            <a href="{{ route('users') }}"> <i class="fa-solid fa-users-gear text-white drop-shadow-lg"></i>
            </a>

        </div>

        </div>
    </footer>