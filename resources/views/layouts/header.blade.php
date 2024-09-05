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
    <link rel="icon" href="{{ asset('asset/logo/MDC-logo-clipped.png') }}" type="image/png">

    @if($setting->darkMode == true)

        <!-- dark mode -->

        <style>
            #sidebar {
                background-color: #2d2d2d;
            }

            #navbar {
                background-color: #6b7280;
                color: white;
            }

            #navbar i {
                color: white;
            }

            #content {
                background-color: transparent;
            }

            #sidebar-right {
                background-color: #2d2d2d;
                color: white;
            }

            #dropdownMenu {
                background-color: #6b7280;
                color: white;
            }

            #notification-dropdown {
                background-color: #6b7280;
                color: white;
            }

            #messages-dropdown {
                background-color: #6b7280;
                color: black;
            }

            #contact-list {
                color: white;
            }

            #user-dropdown {
                background-color: #6b7280;
                color: white;
            }

            #main-content {
                background-color: #000000;
            }

            #calendar-grid {
                background-color: #000;
            }
        </style>

    @else

        <style>
            #content {
                background: linear-gradient(to right, #00bcd4, #006064);

            }

            #sidebar {

                background: linear-gradient(to bottom, #4f8ef7, #003366);
            }
        </style>

    @endif
    <style>
        /* Animation for opening */
        .animation-open {
            animation-name: fadeIn, zoomIn;
            animation-duration: 400ms, 400ms;
            animation-timing-function: ease, ease;
            animation-fill-mode: forwards;
        }

        /* Animation for closing */
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
</style>

    <style>
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

        .slider {
            position: relative;
            width: 60px;
            height: 32px;
        }

        .slider-track {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: background-color 0.3s;
            border-radius: 9999px;
        }

        .slider-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 28px;
            height: 28px;
            background-color: white;
            border-radius: 9999px;
            transition: transform 0.3s;
        }

        .slider input:checked+.slider-track {
            background-color: #2196F3;
        }

        .slider input:checked+.slider-track .slider-thumb {
            transform: translateX(26px);
        }
        body{

           

        }
    </style>

</head>

<body class="font-verdana flex h-screen overflow-hidden bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <div id="sidebar-right"
        class="shadow-md fixed top-0 right-0 h-full w-64 bg-white transform translate-x-full z-50 {{ $setting->transition == true ? 'transition-transform duration-[500ms] ease-in-out' : '' }}">
        <div class="p-4">
            <h2 class="text-2xl font-bold">Display Settings</h2>

            <!-- Dark Mode Toggle -->
            <form action="{{ route('darkMode') }}" method="POST">
                @csrf
                <div class="flex items-start mt-4">
                    <div class="px-2.5 py-1 m-2 text-4xl bg-gray-500 rounded-full">
                        <div class="fa-solid fa-moon text-white"></div>
                    </div>
                    <div class="flex flex-col flex-wrap">
                        <p class="font-bold">Dark mode</p>
                        <p>Modify App's appearance to minimize glare and give your eyes some relief.</p>
                        <div class="flex items-center justify-between mt-2">
                            <label class="slider">
                                <input id="dark-mode-slider" type="checkbox" name="action" class="sr-only" {{ $setting->darkMode ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <div class="slider-track">
                                    <div class="slider-thumb"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Transitions Toggle -->
            <form action="{{ route('transitions') }}" method="POST">
                @csrf
                <div class="flex items-start mt-4">
                    <div class="px-1.5 py-1 m-2 text-4xl bg-gray-500 rounded-full">
                        <div class="text-white fa-solid fa-arrows-left-right"></div>
                    </div>
                    <div class="flex flex-col flex-wrap">
                        <p class="font-bold">Transitions</p>
                        <p>Add smooth transition animations, even if it results slower system performance.</p>
                        <div class="flex items-center justify-between mt-2">
                            <label class="slider">
                                <input id="transition-slider" type="checkbox" name="action" class="sr-only" {{ $setting->transition ? 'checked' : '' }} onchange="this.form.submit()">
                                <div class="slider-track">
                                    <div class="slider-thumb"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <div id="toggle-container" class="fixed right-1 bottom-[45%] z-50">
        <button id="open-btn" title="Display Settings"
            class="{{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-110' : '' }} border border-gray-300 shadow-xl toggle-button px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg shadow-lg">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>



    <script>
        // JavaScript to handle sidebar toggle
        document.getElementById('open-btn').addEventListener('click', function () {
            document.getElementById('sidebar-right').classList.toggle('translate-x-full');
            document.getElementById('open-btn').classList.toggle('mr-[260px]');

        });


    </script>

    <!-- Sidebar -->
    <div id="sidebar"
        class="h-full flex flex-col text-white shadow-lg w-20 {{ $setting->transition == true ? 'transition-all duration-[300ms] ease-in-out' : '' }}">
        <!--  -->
        <div class="space-y-16">
            <!-- center contents of the sidebar -->
            <div class="flex justify-center items-center flex-col">
                <!-- Logo and Label -->
                <div class="flex flex-col items-center  mt-4">
                    <img class="w-12 h-12" src="{{asset('asset/photos/logo.png')}}" alt="Logo">
                    <span id="logoLabel" class="ml-4 text-sm text-center hidden">MDC - Property Rental & <br>Reservation
                        System</span>
                </div>

                <div id="menu-items" class="mt-10 space-y-6">
                    <a href="{{ route('dashboard') }}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Dashboard">
                        <i
                            class="fa-solid fa-gauge fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Dashboard</span>
                    </a>
                    <a href="{{ route('items')}}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Items">
                        <i
                            class="fa-solid fa-boxes-stacked fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Items</span>
                    </a>
                    <a href="{{ route('categories')}}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Categories">
                        <i
                            class="fas fa-th-large fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Categories</span>
                    </a>
                    <a href="{{ route('transactions')}}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Transactions">
                        <i
                            class="fa-solid fa-business-time fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Transactions</span>
                    </a>
                    <a href="{{ route('transactions')}}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Manage Users">
                        <i
                            class="fa-solid fa-users-gear fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Manage Users</span>
                    </a>
                    <a href="{{ route('transactions')}}"
                        class="w-full p-3 flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                        title="Manage Roles">
                        <i
                            class="fa-solid fa-user-pen fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="ml-4 text-sm hidden">Manage Roles</span>
                    </a>


                </div>



            </div>
            <div class="flex justify-center">
                <button title="Expand | Shrink" id="toggle-button" onclick="toggleSidebar()"
                    class="px-[10px] py-[9px]  flex items-center justify-center bg-white hover:bg-gray-400 rounded-full transition duration-200 transition-transform duration-300 ease-in-out transform hover:scale-110">
                    <i class="fa-solid fa-arrow-right text-black"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar -->
        <div id="navbar" class="flex items-center justify-between bg-white p-4 shadow-md relative">
            <!-- App Naddme -->
            <div class="flex items-center space-x-2">
                <span class="text-lg font-semibold"> {{ $page_title }}</span>
            </div>

            <!-- Right-side icons -->
            <div class="flex items-center space-x-6 relative">
                <!-- Notification Icon -->
                <div class="relative" id="inside-notification" title="Notifications">
                    <!-- Notification Button -->
                    <button id="notification-icon" class="hover:text-gray-300">
                        <i class="fa-solid fa-bell fa-lg text-blue-600"></i>
                        <span>Notifications</span>
                        @if($unreadNotifications > 0)
                            <span id="notification-count"
                                class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full -translate-x-1/2 -translate-y-1/2">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" 
                        class="rounded absolute right-0 mt-2 hidden w-[30rem] bg-white p-2 shadow-lg border border-gray-200 z-50">
                        <div>
                            <h1 class="text-2xl font-bold">Notifications</h1>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="font-medium p-2">
                                <button id="all"
                                    onclick="document.getElementById('all').classList.add('bg-gray-300'); document.getElementById('unread').classList.remove('bg-gray-300')"
                                    hx-get="{{ route('notificationList', ['filter' => 'all'])}}" hx-swap="innerHTML"
                                    hx-trigger="click" hx-indicator="#loader" hx-target="#notification-list"
                                    class="px-2 rounded-full bg-gray-300 hover:bg-gray-200">
                                    All
                                </button>


                                <button id="unread"
                                    onclick="document.getElementById('unread').classList.add('bg-gray-300'); document.getElementById('all').classList.remove('bg-gray-300');"
                                    hx-get="{{ route('notificationList', ['filter' => 'unread'])}}" hx-swap="innerHTML"
                                    hx-trigger="click" hx-indicator="#loader" hx-target="#notification-list"
                                    class="px-2 rounded-full hover:bg-gray-200">
                                    Unread
                                </button>
                            </div>

                            <div class="relative inline-block text-left">
                                <button id="dropdownButton" class="focus:outline-none">
                                    <i class="text-gray-500 hover:bg-gray-200 p-2 fas fa-ellipsis rounded-full"></i>
                                </button>
                                <div id="dropdownMenu"
                                    class="dropdown-content absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg">
                                    <a href="{{ route('readAll') }}" class="block px-4 py-2 rounded hover:bg-gray-100">
                                        <i class="text-blue-500 fas fa-check-circle mr-2"></i> Mark as all read
                                    </a>
                                    <a href="{{ route('deleteAll') }}"
                                        class="block px-4 py-2 rounded hover:bg-gray-100">
                                        <i class="text-blue-500 fas fa-trash mr-2"></i> Delete All
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div id="loader"
                            class="rounded  bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center z-50 hidden">

                            <img src="{{asset('asset/loader/loading.gif')}}" alt="Loading..." class="w-16 h-16">
                        </div>

                        <div id="notification-list"
                            class="z-10 flex flex-col max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-100">

                            @include('pages.partials.notification-list')

                            <!-- "See More" Button -->
                            @if(count($notifications) > 5)
                                <button id="see-more-btn"
                                    class="w-full p-2 text-blue-600 cursor-pointer hover:bg-blue-100 transition duration-150 ease-in-out">
                                    See More
                                </button>
                            @endif

                        </div>
                    </div>

                    <!-- Messages Icon -->
                    @if($page_title != 'Messages')
                        <div class="relative" id="inside-messages" title="Messages">
                            <button id="messages-icon" class="flex items-center focus:outline-none">
                                <i class="fa-solid fa-envelope fa-lg text-blue-600"></i>
                                <span class="ml-2 hover:text-gray-300">Messages</span>

                                @if($unreadMessages > 0)
                                    <span id="notification-count"
                                        class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform -translate-x-1/2 -translate-y-1/2">
                                        {{ $unreadMessages }}
                                    </span>
                                @endif
                            </button>

                            <!-- Messages Dropdown Menu -->
                            <div id="messages-dropdown"
                                class="absolute right-0 hidden mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                                <div class="p-2">
                                    <form hx-get="{{ route('contacts') }}" hx-trigger="input" hx-swap="innerHTML"
                                        hx-target="#contact-list"
                                        class="flex justiify-around px-2 items-center bg-gray-200 rounded-full">

                                        <div class="p-2">
                                            <div class="fas fa-search text-black"></div>
                                        </div>

                                        <input type="text" name="searchValue" placeholder="Search contact"
                                            class="placeholder-center mr-8 focus:outline-none bg-transparent">
                                    </form>
                                </div>
                                <div class="p-2 max-h-80 overflow-y-auto custom-scrollbar">

                                    <ul id="contact-list" class="list-none">
                                        @include('pages.partials.contact-list')
                                    </ul>

                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- User Icon -->
                    <div id="inside-user" class="relative" title="Profile">
                        <button id="user-icon">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-user fa-lg text-blue-600 cursor-pointer"></i>
                                <h1 class="hover:text-gray-200">Juan Dela Cruz</h1>

                            </div>

                        </button>


                        <!-- User Dropdown Menu -->
                        <div id="user-dropdown"
                            class="absolute right-0 hidden mt-2 border p-2 border-gray-300 bg-white rounded-lg   shadow-xl z-10">
                            <div class="flex flex-col space-y-2">


                                <div class="p-2 cursor-pointer hover:bg-gray-200 rounded-lg">
                                    <i class="text-blue-500 fas fa-user mr-2"></i> Profile
                                </div>
                                <div class="p-2 cursor-pointer hover:bg-gray-200 rounded-lg">
                                    <i class="text-blue-500 fas fa-cog mr-2"></i> Settings
                                </div>
                                <div class="p-2 cursor-pointer hover:bg-gray-200 rounded-lg">
                                    <i class="text-blue-500 fas fa-sign-out-alt mr-2"></i> Logout
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            @yield('content')


            <script>

                document.body.addEventListener('htmx:beforeRequest', function () {
                    document.getElementById('loader').classList.remove('hidden');
                });

                document.body.addEventListener('htmx:afterRequest', function () {
                    document.getElementById('loader').classList.add('hidden');
                });

                function showModal(modalId) {
                    if (modalId) {
                        document.getElementById(modalId).classList.remove('hidden');
                    }
                }

                function hideModal(modalId) {
                    if (modalId) {
                        document.getElementById(modalId).classList.add('hidden');
                    }
                }

                const sidebar = document.getElementById('sidebar');
                const menuItems = document.getElementById('menu-items');
                const toggleButton = document.getElementById('toggle-button');

                const notificationIcon = document.getElementById('notification-icon');
                const notificationDropdown = document.getElementById('notification-dropdown');

                const userIcon = document.getElementById('user-icon');
                const userDropdown = document.getElementById('user-dropdown');

                const messageIcon = document.getElementById('messages-icon');
                const messageDropdown = document.getElementById('messages-dropdown');

                const logoLabel = document.getElementById('logoLabel');

                toggleButton.addEventListener('click', () => {
                    sidebar.classList.toggle('w-64');
                    sidebar.classList.toggle('w-20');
                    toggleButton.querySelector('i').classList.toggle('fa-arrow-left');
                    toggleButton.querySelector('i').classList.toggle('fa-arrow-right');
                    logoLabel.classList.toggle('hidden');

                    const isExpanded = sidebar.classList.contains('w-64');
                    [...menuItems.children].forEach(item => {
                        item.classList.toggle('justify-center', !isExpanded);
                        item.classList.toggle('pl-4', isExpanded);
                        item.querySelector('span').classList.toggle('hidden', !isExpanded);
                    });
                });

                notificationIcon.addEventListener('click', () => {
                    notificationDropdown.classList.toggle('hidden');
                });

                userIcon.addEventListener('click', () => {
                    userDropdown.classList.toggle('hidden');
                });

                messageIcon.addEventListener('click', () => {
                    messageDropdown.classList.toggle('hidden');
                });

                document.addEventListener('DOMContentLoaded', function () {

                    const seeMoreBtn = document.getElementById('see-more-btn');
                    const notificationList = document.getElementById('notification-list');
                    const notificationDropdown = document.getElementById('notification-dropdown');
                    const messagesDropdown = document.getElementById('messages-dropdown');
                    const userDropdown = document.getElementById('user-dropdown');
                    const insideUser = document.getElementById('inside-user');
                    const insideNotification = document.getElementById('inside-notification');
                    const insideMessages = document.getElementById('inside-messages');

                    if (seeMoreBtn) {
                        seeMoreBtn.addEventListener('click', function () {
                            if (notificationList.classList.contains('max-h-64')) {
                                notificationList.classList.remove('max-h-64');
                                notificationList.classList.add('max-h-[calc(100vh-8rem)]');
                                seeMoreBtn.textContent = 'See Less';

                                const rect = dropdown.getBoundingClientRect();
                                const viewportHeight = window.innerHeight;

                                if (rect.bottom > viewportHeight) {
                                    dropdown.style.top = `-${rect.bottom - viewportHeight}px`;
                                }
                            } else {
                                notificationList.classList.remove('max-h-[calc(100vh-8rem)]');
                                notificationList.classList.add('max-h-64');
                                seeMoreBtn.textContent = 'See More';
                            }
                        });
                    }

                    document.addEventListener('click', function (event) {
                        const clickedElement = event.target;

                        // Hide user dropdown if click is outside of it
                        if (!userDropdown.contains(clickedElement) && !insideUser.contains(clickedElement)) {
                            userDropdown.classList.add('hidden');
                        }

                        // Hide notification dropdown if click is outside of it
                        if (!notificationDropdown.contains(clickedElement) && !insideNotification.contains(clickedElement)) {
                            notificationDropdown.classList.add('hidden');
                        }

                        // Hide notification dropdown if click is outside of it
                        if (!messagesDropdown.contains(clickedElement) && !insideMessages.contains(clickedElement)) {
                            messagesDropdown.classList.add('hidden');
                        }
                    });

                    const button = document.getElementById('dropdownButton');
                    const menu = document.getElementById('dropdownMenu');
                    const messagesButton = document.getElementById('dropdownMessages');

                    button.addEventListener('click', function () {
                        menu.classList.toggle('hidden');
                    });


                    document.addEventListener('click', function (event) {
                        if (!button.contains(event.target) && !menu.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });

                });
</script>