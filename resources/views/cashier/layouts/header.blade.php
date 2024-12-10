<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/photos/logo.png') }}" type="image/png">

    <!-- JavaScript Libraries -->
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>

    @hasrole('admin|superadmin|staff')
    <script>
        window.location.href = "{{ route('admin.home') }}"; 
    </script>
    @endhasrole

    <style>
        @media(orientation:portrait) {
            #notif-label {
                display: none;
            }

            #message-label {
                display: none;
            }

        }
    </style>

</head>

<body class="overflow-hidden">
    @include('admin.components.rightbar')
    <header class="flex items-center p-2 space-x-2 bg-gradient-to-r from-blue-500 to-blue-800 w-full shadow-xl">
        <div class="flex w-full space-x-2 items-center">
            <div class="shadow-md rounded-full">
                <img src="{{ asset('asset/logo/logo.png') }}" class="h-[25x] w-[25px]" alt="">
            </div>

            <span class="py-3 border-r-2"></span>
            <h1 class="text-blue-100 font-bold">MDC - PRMS</h1>
        </div>

        @include('cashier.scripts.message-count')


        <div class="flex items-center w-full justify-end space-x-4">
            <div class="flex space-x-4">

                <button id="messageIcon" hx-get="{{route('admin.contacts-refresh')}}" hx-swap="innerHTML"
                    hx-target="#messagesDropdown" hx-trigger="click"
                    class="text-white cursor-pointer hover:opacity-50 relative">
                    <i class="fas fa-inbox fa-lg"></i>
                    <span id="message-label">Messages</span>

                    <span id="message-count"
                        class="absolute top-2 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                        {{ $unreadMessages }}
                    </span>

                </button>

                <div id="messagesDropdown"
                    class="absolute right-3 top-16 w-96 bg-white border border-gray-300 rounded shadow-lg hidden z-50">
                </div>

                <button id="userIcon" class="relative flex items-center focus:outline-none hover:opacity-50">
                    <div class="flex items-center space-x-2">
                        <div class="">
                            <div class="">
                                <img class="h-10 w-10 rounded-full"
                                    src="{{ Storage::exists('public/images/users/' . Auth::user()->img) ? asset('storage/images/users/' . Auth::user()->img) : asset('asset/photos/user.png') }}"
                                    alt="User Image">
                            </div>
                            <div
                                class="absolute bottom-0 right-0 transform translate-x-1 translate-y-1 text-gray-300 shadow-md">
                                <i class="fas fa-chevron-circle-down "></i>
                            </div>

                        </div>
                    </div>
                </button>

                <!-- User Dropdown -->
                <div id="userDropdownMenu"
                    class="absolute right-3 top-16 w-48 bg-white border border-gray-300 rounded shadow-lg hidden z-50">
                    <a href="/profile" class="block px-4 py-2 text-black hover:bg-gray-200">{{ Auth::user()->name }}</a>
                    <span class="block px-4 py-2 text-black hover:bg-gray-200 cursor-pointer"
                        onclick="document.getElementById('logoutConfirm').classList.remove('hidden')">Logout</span>
                </div>
            </div>


    </header>
    <div class="bg-blue-200 p-2">


        <ul class="flex space-x-4">
            <a href="{{ route('cashier.home') }}">
                <li
                    class="{{ $page_title == 'Home' ? 'hover:opacity-50 font-bold' : 'opacity-50 hover:opacity-100'}}  cursor-pointer">
                    Home</li>
            </a>
            <a href="{{ route('cashier.reservations') }}">
                <li
                    class="{{ $page_title == 'Reservations' ? 'hover:opacity-50 font-bold' : 'opacity-50 hover:opacity-100'}} cursor-pointer">
                    Reservations</li>
            </a>
            <a href="{{ route('cashier.transactions') }}">
                <li
                    class="{{ $page_title == 'Transactions' ? 'hover:opacity-50 font-bold' : 'opacity-50 hover:opacity-100'}} cursor-pointer">
                    Transactions</li>
            </a>

        </ul>
    </div>

    @include('cashier.modals.message-new')

    @yield('content')



    <div id="logoutConfirm"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded w-[500px] mx-2">
            <div class="bg-red-500 py-1 rounded-t"></div>
            <div class="flex p-4 space-x-4 items-center border-b-2">
                <div>
                    <i class="fa-solid fa-triangle-exclamation fa-2xl text-red-500"></i>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-2xl font-medium">Logout</h1>
                    <span>Are you sure to logout?</span>
                </div>
            </div>
            <div class="flex p-2 justify-end space-x-2 bg-gray-100 rounded-b">
                <button onclick="document.getElementById('logoutConfirm').classList.add('hidden')" type="button"
                    class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">Cancel</button>
                <a href="{{ route('logout') }}" type="button"
                    class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Logout</a>
            </div>
        </div>
    </div>
    <script>
        // Dropdown toggle functionality
        const dropdownToggle = document.getElementById('userIcon');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        const messagesDropdown = document.getElementById('messagesDropdown');


        // Close all dropdowns
        const closeAllDropdowns = () => {
            userDropdownMenu.classList.add('hidden');
            messagesDropdown.classList.add('hidden');

        };

        // Toggle dropdown visibility
        const toggleDropdown = (dropdown) => {
            closeAllDropdowns(); // Close other dropdowns
            dropdown.classList.toggle('hidden');
        };

        dropdownToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown(userDropdownMenu);
        });

        document.getElementById('messageIcon').addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown(messagesDropdown);
        });



        // Click outside to close dropdowns
        document.addEventListener('click', (event) => {
            const clickedElement = event.target;

            // Check if the click is outside any dropdowns
            if (!userDropdownMenu.contains(clickedElement) &&
                !messagesDropdown.contains(clickedElement) &&
                !dropdownToggle.contains(clickedElement) &&
                !document.getElementById('messageIcon').contains(clickedElement)) {
                closeAllDropdowns();
            }
        });
    </script>


</body>

</html>