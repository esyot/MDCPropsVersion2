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


        <div class="flex items-center w-full justify-end space-x-4">
            <div class="">
                <button id="notificationIcon" class="text-white cursor-pointer hover:opacity-50 relative">
                    <i class="fas fa-bell fa-lg"></i>
                    <span id="notif-label">Notifications</span>
                    @if($unreadNotifications > 0)
                        <span id="notification-count"
                            class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </button>



                <!-- Notification Dropdown -->
                <div id="notificationsDropdown"
                    class="absolute p-2 right-3 top-14 w-[350px] bg-white border border-gray-300 rounded shadow-lg z-50 hidden">
                    <div>
                        <h1 class="text-2xl font-bold">Notifications</h1>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="font-medium p-2">
                            <button
                                onclick="this.classList.toggle('bg-gray-300'); document.getElementById('unread').classList.toggle('bg-gray-300');"
                                id="all" class="px-2 rounded-full bg-gray-300 hover:bg-gray-200">All</button>
                            <button
                                onclick="this.classList.toggle('bg-gray-300'); document.getElementById('all').classList.toggle('bg-gray-300');"
                                id="unread" class="px-2 rounded-full hover:bg-gray-200">Unread</button>
                        </div>
                        <div id="loader"
                            class="rounded bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center hidden">
                            <img src="{{asset('asset/loader/loading.gif')}}" alt="Loading..." class="w-16 h-16">
                        </div>
                        <div class="relative inline-block text-left">
                            <button id="notificationOptionButton" class="focus:outline-none">
                                <i class="text-gray-500 hover:bg-gray-200 p-2 fas fa-ellipsis rounded-full"></i>
                            </button>
                            <div id="notificationOptionDropdown"
                                class="dropdown-content absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                                <a href="{{ route('notifications.read-all') }}"
                                    class="block px-4 py-2 rounded hover:bg-gray-100">
                                    <i class="text-blue-500 fas fa-check-circle mr-2"></i> Mark as all read
                                </a>
                                <a href="{{ route('notifications.delete-all') }}"
                                    class="block px-4 py-2 rounded hover:bg-gray-100">
                                    <i class="text-blue-500 fas fa-trash mr-2"></i> Delete All
                                </a>
                            </div>

                        </div>
                    </div>
                    <div id="notifications-list"
                        class="z-10 flex flex-col max-h-[200px] overflow-y-auto custom-scrollbar">
                        @include('cashier.partials.notification-list')
                        @if(count($notifications) > 5)
                            <button id="see-more-btn"
                                class="w-full p-2 text-blue-600 cursor-pointer hover:bg-blue-100 transition duration-150 ease-in-out">See
                                More</button>
                        @endif
                    </div>
                </div>
                <script>
                    function showLoader() {
                        document.getElementById('loader').classList.remove('hidden');
                    }

                    function hideLoader() {
                        document.getElementById('loader').classList.add('hidden');
                    }

                    document.getElementById('all').addEventListener('click', function () {
                        showLoader();

                        htmx.ajax('GET', '{{ route('cashier.notifications-filter', ['action' => 'all']) }}', {
                            target: '#notifications-list',
                            swap: 'innerHTML'
                        });
                    });

                    document.getElementById('unread').addEventListener('click', function () {
                        showLoader();

                        htmx.ajax('GET', '{{ route('cashier.notifications-filter', ['action' => 'unread']) }}', {
                            target: '#notifications-list',
                            swap: 'innerHTML'
                        });
                    });


                    document.body.addEventListener('htmx:afterRequest', function () {
                        hideLoader();
                    });

                    notificationOptionButton.addEventListener('click', (event) => {
                        event.stopPropagation();
                        notificationOptionDropdown.classList.toggle('hidden');
                    });


                </script>


                <button id="messageIcon" class="text-white cursor-pointer hover:opacity-50 relative">
                    <i class="fas fa-inbox fa-lg"></i>
                    <span id="message-label">Messages</span>

                    <span id="notification-count"
                        class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                        {{ $unreadMessages }}
                    </span>

                </button>

                <!-- Messages Dropdown -->
                <div id="messagesDropdown"
                    class="absolute p-2 right-3 top-14 w-[350px] bg-white border border-gray-300 rounded shadow-lg z-50 hidden">
                    <h1 class="text-2xl font-bold">Messages</h1>
                    <div id="messages-list" class="max-h-[200px] overflow-y-auto custom-scrollbar">
                        @include('cashier.partials.contact-list')
                    </div>


                    <button id="userIcon" class="relative flex items-center focus:outline-none hover:opacity-50">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <img class=" h-[40px] w-[40px] rounded-full"
                                    src="{{ Storage::exists('public/images/users/' . Auth::user()->img) ? asset('storage/images/users/' . Auth::user()->img) : asset('asset/photos/user.png') }}"
                                    alt="User Image">
                                <div
                                    class="absolute bottom-0 right-0 transform translate-x-1 translate-y-1 text-gray-300 shadow-md">
                                    <i class="fas fa-chevron-circle-down "></i>
                                </div>

                            </div>
                        </div>
                    </button>



                    <!-- User Dropdown -->
                    <div id="userDropdownMenu"
                        class="absolute right-3 top-14 w-48 bg-white border border-gray-300 rounded shadow-lg hidden z-50">
                        <a href="/profile"
                            class="block px-4 py-2 text-black hover:bg-gray-200">{{ Auth::user()->name }}</a>
                        <span class="block px-4 py-2 text-black hover:bg-gray-200 cursor-pointer"
                            onclick="document.getElementById('logoutConfirm').classList.remove('hidden')">Logout</span>
                    </div>



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
        const notificationsDropdown = document.getElementById('notificationsDropdown');

        // Close all dropdowns
        const closeAllDropdowns = () => {
            userDropdownMenu.classList.add('hidden');
            messagesDropdown.classList.add('hidden');
            notificationsDropdown.classList.add('hidden');
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

        document.getElementById('notificationIcon').addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown(notificationsDropdown);
        });

        // Click outside to close dropdowns
        document.addEventListener('click', (event) => {
            const clickedElement = event.target;

            // Check if the click is outside any dropdowns
            if (!userDropdownMenu.contains(clickedElement) &&
                !messagesDropdown.contains(clickedElement) &&
                !notificationsDropdown.contains(clickedElement) &&
                !dropdownToggle.contains(clickedElement) &&
                !document.getElementById('messageIcon').contains(clickedElement) &&
                !document.getElementById('notificationIcon').contains(clickedElement)) {
                closeAllDropdowns();
            }
        });
    </script>


</body>

</html>