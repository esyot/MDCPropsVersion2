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

    <style>
        /* Custom scrollbar styles */
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

        /* Sidebar styles */
        .sidebar {}

        /* Navbar styles */
        .navbar {}

        /* Main content styles */
        .main-content {}

        .main-content.expanded {
            margin-left: 18rem;
        }

        .sidebar.expanded {
            width: 18rem;
        }

        .navbar.expanded {
            left: 18rem;
        }

        .placeholder-center::placeholder {
            text-align: center;
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-gradient-to-b from-blue-500 to-blue-800 fixed top-0 left-0 bottom-0 w-20 overflow-y-auto z-5 transition-all duration-300 ease-in-out sidebar p-4">

        <div class="flex justify-between items-center flex-col">
            <!-- Logo and Label -->
            <div class="flex flex-col items-center">
                <img class="w-12 h-12" src="{{ asset('asset/photos/logo.png') }}" alt="Logo">
                <span id="logoLabel" class="flex justify-center text-white ml-4 text-sm text-center hidden">
                    MDC - Property Rental & <br>Reservation System
                </span>
            </div>

            <!-- Menu Items -->
            <div id="menu-items" class="mt-10 space-y-6 flex flex-col transition-transform duration-600 ease">
                <a href="{{ route('dashboard') }}"
                    class="w-full p-3 flex ml-1 items-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                    title="Dashboard">
                    <i
                        class="fa-solid fa-gauge fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="ml-4 text-sm hidden">Dashboard</span>
                </a>

                <a href="{{ route('transactions') }}"
                    class="w-full p-3 ml-1 flex items-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                    title="Pending requests">
                    <i
                        class="fa-solid fa-list fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="ml-4 text-sm hidden">Pending Requests</span>
                </a>

                <a href="#"
                    class="w-full p-3 flex items-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                    title="Manage Roles">
                    <i
                        class="fa-solid fa-users-gear fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="ml-4 text-sm hidden">Manage Roles</span>
                </a>

                <a href="#"
                    class="w-full p-3 flex items-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                    title="Manage Users">
                    <i
                        class="fas fa-users fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="ml-4 text-sm hidden">Manage Users</span>
                </a>
            </div>

            <!-- Sidebar Toggle Button -->
            <div class="flex justify-center items-center mt-16">
                <button id="sidebarToggle" title="Expand | Shrink"
                    class="p-2 flex items-center justify-center bg-blue-500 hover:bg-blue-700 rounded-full transition duration-200 transition-transform duration-300 ease-in-out transform hover:scale-110">
                    <i class="fa-solid fa-arrow-right text-white"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Navigation Bar -->
    <div id="navbar"
        class="navbar fixed top-0 left-20 right-0 z-10 bg-white text-black p-4 transition-all duration-300 ease-in-out bg-gray-100 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            <!-- Page Title -->
            <div class="text-2xl font-bold text-gray-800">
                {{ $page_title }}
            </div>

            <div class="flex items-center space-x-6">

                <!-- Right-side icons -->
                <div class="flex items-center space-x-6 relative">
                    <!-- Notification Icon -->
                    <div class="relative" id="inside-notification">
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
                                        onclick="document.getElementById('all').classList.add('bg-blue-300'); document.getElementById('unread').classList.remove('bg-blue-300')"
                                        hx-get="{{ route('notificationList', ['filter' => 'all']) }}"
                                        hx-swap="innerHTML" hx-trigger="click" hx-indicator="#loader"
                                        hx-target="#notification-list"
                                        class="px-2 rounded-full text-blue-500 bg-blue-300 hover:bg-blue-200">
                                        All
                                    </button>

                                    <button id="unread"
                                        onclick="document.getElementById('unread').classList.add('bg-blue-300'); document.getElementById('all').classList.remove('bg-blue-300');"
                                        hx-get="{{ route('notificationList', ['filter' => 'unread']) }}"
                                        hx-swap="innerHTML" hx-trigger="click" hx-indicator="#loader"
                                        hx-target="#notification-list"
                                        class="px-2 text-blue-500 rounded-full hover:bg-blue-200">
                                        Unread
                                    </button>
                                </div>

                                <!-- Dropdown Menu Button -->
                                <div class="relative inline-block text-left">
                                    <button id="dropdownButton" class="focus:outline-none">
                                        <i class="text-blue-500 hover:bg-blue-200 p-2 fas fa-ellipsis rounded-full"></i>
                                    </button>
                                    <div id="dropdownMenu"
                                        class="dropdown-content absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg">
                                        <a href="{{ route('readAll') }}"
                                            class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">
                                            <i class="text-blue-500 fas fa-check-circle mr-2"></i> Mark as all read
                                        </a>
                                        <a href="{{ route('deleteAll') }}"
                                            class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">
                                            <i class="text-blue-500 fas fa-trash mr-2"></i> Delete All
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div id="loader"
                                class="rounded bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center z-50 hidden">
                                <img src="{{ asset('asset/loader/loading.gif') }}" alt="Loading..." class="w-16 h-16">
                            </div>

                            <div id="notification-list"
                                class="z-10 flex flex-col max-h-64 overflow-y-auto custom-scrollbar">
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
                    </div>

                    <!-- Messages Icon -->
                    @if($page_title != 'Messages')
                        <div class="relative" id="inside-notification">
                            <button id="messages-icon" class="flex items-center hover:text-gray-300 focus:outline-none">
                                <i class="fa-solid fa-envelope fa-lg text-blue-600"></i>
                                <span class="ml-2">Messages</span>

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
                                <div class="p-2 max-h-80 overflow-y-auto custom-scrollbar">
                                    <div class="relative m-2">
                                        <form hx-get="{{ route('contacts') }}" hx-trigger="input" hx-swap="innerHTML"
                                            hx-target="#contact-list"
                                            class="flex justiify-around px-2 items-center bg-gray-100 rounded-full">
                                            <div class="p-2">
                                                <i class="fas fa-search text-gray-500"></i>
                                            </div>
                                            <input type="text" name="searchValue" placeholder="Search contact"
                                                class="placeholder-center mr-8 focus:outline-none bg-transparent">
                                        </form>
                                    </div>
                                    <ul id="contact-list" class="list-none">
                                        @include('pages.partials.contact-list')
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- User Icon -->
                    <div id="inside-user" class="relative">
                        <button id="user-icon">
                            <i class="fa-solid fa-user fa-lg text-blue-600 cursor-pointer"></i>
                            {{ $current_user_name }}
                        </button>

                        <!-- User Dropdown Menu -->
                        <div id="user-dropdown"
                            class="absolute right-0 hidden mt-2 p-2 border border-gray-300 bg-white rounded shadow-lg z-10">
                            <div class="p-2 text-gray-800 cursor-pointer hover:bg-gray-200 rounded">
                                <i class="text-blue-500 fas fa-user mr-2"></i> Profile
                            </div>
                            <div class="p-2 text-gray-800 cursor-pointer hover:bg-gray-200 rounded">
                                <i class="text-blue-500 fas fa-cog mr-2"></i> Settings
                            </div>
                            <div class="p-2 text-gray-800 cursor-pointer hover:bg-gray-200 rounded">
                                <i class="text-blue-500 fas fa-sign-out-alt mr-2"></i> Logout
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex">
        @yield('content')
    </div>

    <!-- JavaScript -->
    <script>
        // Sidebar and Navbar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const navbar = document.getElementById('navbar');
        const toggleButton = document.getElementById('sidebarToggle');
        const menuItems = document.getElementById('menu-items');
        const logoLabel = document.getElementById('logoLabel');

        toggleButton.addEventListener('click', () => {
            const isExpanded = sidebar.classList.contains('expanded');

            // Toggle sidebar and related elements
            sidebar.classList.toggle('expanded', !isExpanded);
            mainContent.classList.toggle('expanded', !isExpanded);
            navbar.classList.toggle('expanded', !isExpanded);

            // Toggle icon classes
            const icon = toggleButton.querySelector('i');
            if (icon) {
                icon.classList.remove(isExpanded ? 'fa-arrow-left' : 'fa-arrow-right');
                icon.classList.add(isExpanded ? 'fa-arrow-right' : 'fa-arrow-left');
            }

            // Toggle menu item spans and logo label visibility
            [...menuItems.children].forEach(item => {
                const span = item.querySelector('span');
                if (span) {
                    span.classList.toggle('hidden', isExpanded);
                }
            });
            if (logoLabel) {
                logoLabel.classList.toggle('hidden', isExpanded);
            }
        });

        // Loader animation for loading notifications during HTMX requests
        document.body.addEventListener('htmx:beforeRequest', function () {
            document.getElementById('loader').classList.remove('hidden');
        });

        document.body.addEventListener('htmx:afterRequest', function () {
            document.getElementById('loader').classList.add('hidden');
        });

        // Notification and User Dropdown Menus
        const notificationIcon = document.getElementById('notification-icon');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const userIcon = document.getElementById('user-icon');
        const userDropdown = document.getElementById('user-dropdown');

        notificationIcon.addEventListener('click', () => {
            notificationDropdown.classList.toggle('hidden');
        });

        userIcon.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });



        // Hide dropdowns when clicking outside
        document.addEventListener('click', function (event) {
            const clickedElement = event.target;

            if (!userDropdown.contains(clickedElement) && !document.getElementById('inside-user').contains(clickedElement)) {
                userDropdown.classList.add('hidden');
            }

            if (!notificationDropdown.contains(clickedElement) && !document.getElementById('inside-notification').contains(clickedElement)) {
                notificationDropdown.classList.add('hidden');
            }
        });

        // "See More" Button functionality for notifications
        document.addEventListener('DOMContentLoaded', function () {
            const seeMoreBtn = document.getElementById('see-more-btn');
            const notificationList = document.getElementById('notification-list');

            if (seeMoreBtn) {
                seeMoreBtn.addEventListener('click', function () {
                    if (notificationList.classList.contains('max-h-64')) {
                        notificationList.classList.remove('max-h-64');
                        notificationList.classList.add('max-h-[calc(100vh-8rem)]');
                        seeMoreBtn.textContent = 'See Less';
                    } else {
                        notificationList.classList.remove('max-h-[calc(100vh-8rem)]');
                        notificationList.classList.add('max-h-64');
                        seeMoreBtn.textContent = 'See More';
                    }
                });
            }

            // Dropdown Menu for settings
            const button = document.getElementById('dropdownButton');
            const menu = document.getElementById('dropdownMenu');

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
</body>

</html>