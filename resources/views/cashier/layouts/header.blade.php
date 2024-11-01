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

    @include('admin.components.rightbar')
    <header class="flex items-center p-2 space-x-2 bg-gradient-to-r from-blue-500 to-blue-800 w-full shadow-md">

        <div class="shadow-md rounded-full">
            <img src="{{ asset('asset/logo/logo.png') }}" class="h-[50px] w-[50px]" alt="">

        </div>

        <nav class="flex items-center justify-between w-full">
            <ul class="flex space-x-4 text-white">
                <a href="{{ route('cashier.home') }}">
                    <li class="hover:opacity-50 cursor-pointer">Home</li>
                </a>
                <a href="{{ route('cashier.reservations') }}">
                    <li class="hover:opacity-50 cursor-pointer">Reservations</li>
                </a>
                <li class="hover:opacity-50 cursor-pointer">Transactions</li>
            </ul>
            <div class="relative inline-block">
                <div class="hover:opacity-50 cursor-pointer flex items-center" id="dropdownToggle">
                    <img src="{{ asset('asset/photos/user.png') }}" class="w-[40px] h-[40px]" alt="User">
                    <i
                        class="fas fa-chevron-circle-down absolute bottom-0 right-0 transform translate-x-1 translate-y-1 text-gray-300"></i>

                </div>

                <div id="dropdownMenu" class="right-3 fixed hidden bg-white border rounded shadow-md mt-2">
                    <a href="/profile" class="block px-4 py-2 text-black hover:bg-gray-200">{{Auth::user()->name}}</a>
                    <span class="block px-4 py-2 text-black hover:bg-gray-200 cursor-pointer"
                        onclick="document.getElementById('logoutConfirm').classList.remove('hidden')">Logout</>
                        </s>
                </div>

                <script>
                    const dropdownToggle = document.getElementById('dropdownToggle');
                    const dropdownMenu = document.getElementById('dropdownMenu');

                    dropdownToggle.addEventListener('click', () => {
                        dropdownMenu.classList.toggle('hidden');
                    });

                    // Close the dropdown if clicking outside of it
                    window.addEventListener('click', (event) => {
                        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                            dropdownMenu.classList.add('hidden');
                        }
                    });
                </script>

                <style>
                    .hidden {
                        display: none;
                    }
                </style>


                <div id="logoutConfirm"
                    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden ">

                    <div class="bg-white rounded w-[500px] mx-2">
                        <div class="bg-red-500 py-1 rounded-t">

                        </div>
                        <div class="flex p-4 space-x-4 items-center border-b-2">
                            <div class="">
                                <i class="fa-solid fa-triangle-exclamation fa-2xl text-red-500"></i>
                            </div>
                            <div class="flex flex-col">
                                <h1 class="text-2xl font-medium">Logout</h1>
                                <span>Are you sure to logout?</span>
                            </div>
                        </div>
                        <div class="flex p-2 justify-end space-x-2 bg-gray-100 rounded-b">

                            <button onclick="document.getElementById('logoutConfirm').classList.add('hidden')"
                                type="button"
                                class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">
                                Cancel
                            </button>
                            <a href="{{ route('logout') }}" type="button"
                                class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Logout</a>
                        </div>

                    </div>

                </div>

        </nav>


    </header>

    @yield('content')

   