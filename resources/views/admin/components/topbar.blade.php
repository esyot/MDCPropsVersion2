<!-- Navbar -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between p-4 shadow-md relative">
        <!-- App Name -->
        <div id="pageTitle" class="flex items-center space-x-2">
            <span class="text-lg font-semibold">

                {{$page_title}}

            </span>
        </div>

        <!-- Right-side icons -->
        <div id="topbar-content" class="flex items-center space-x-6 relative">
            <!-- Notification Icon -->
            <div class="relative" id="inside-notification" title="Notifications">
                <!-- Notification Button -->
                <button id="notification-icon" class="hover:opacity-50"
                    hx-get="{{ route('admin.notification-list', ['filter' => 'all'])}}" hx-swap="innerHTML"
                    hx-trigger="click" hx-target="#notification-list">
                    <i class="fa-solid fa-bell fa-lg text-blue-600"></i>
                    <span id="notificationTitle">Notifications</span>

                    <span id="notif-count" id="notification-count"
                        class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full -translate-x-1/2 -translate-y-1/2">
                        {{ $unreadNotifications }}
                    </span>

                </button>

                @include('admin.scripts.notification-count')

                <style>
                    @media (orientation: landscape) {
                        #notification-dropdown {
                            position: absolute;
                            right: 0;
                            width: 400px;
                        }
                    }

                    @media (orientation: portrait) {
                        #notification-dropdown {
                            position: fixed;
                            left: 0;
                            right: 0;
                        }
                    }
                </style>

                <!-- Notification Dropdown -->
                <div id="notification-dropdown"
                    class="rounded mt-2 mx-4 hidden bg-white p-2 shadow-lg border border-gray-200 z-50">
                    <div>
                        <h1 id="title" class="text-2xl font-bold">Notifications</h1>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="font-medium p-2">
                            <button id="all"
                                onclick="document.getElementById('all').classList.add('bg-gray-300'); document.getElementById('unread').classList.remove('bg-gray-300')"
                                hx-get="{{ route('admin.notification-list', ['filter' => 'all'])}}" hx-swap="innerHTML"
                                hx-trigger="click" hx-target="#notification-list"
                                class="px-2 rounded-full bg-gray-300 hover:bg-gray-200">
                                All
                            </button>

                            <button id="unread"
                                onclick="document.getElementById('unread').classList.add('bg-gray-300'); document.getElementById('all').classList.remove('bg-gray-300');"
                                hx-get="{{ route('admin.notification-list', ['filter' => 'unread']) }}"
                                hx-swap="innerHTML" hx-trigger="click" hx-target="#notification-list"
                                class="px-2 rounded-full hover:bg-gray-200">
                                Unread
                            </button>
                        </div>

                        <div id="loader"
                            class="rounded bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center hidden">
                            <img src="{{asset('asset/loader/loading.gif')}}" alt="Loading..." class="w-16 h-16">
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

                                htmx.ajax('GET', '{{ route('admin.notification-list', ['filter' => 'all']) }}', {
                                    target: '#notification-list',
                                    swap: 'innerHTML'
                                });
                            });

                            document.getElementById('unread').addEventListener('click', function () {
                                showLoader();

                                htmx.ajax('GET', '{{ route('admin.notification-list', ['filter' => 'unread']) }}', {
                                    target: '#notification-list',
                                    swap: 'innerHTML'
                                });
                            });


                            document.body.addEventListener('htmx:afterRequest', function () {
                                hideLoader();


                            });

                        </script>




                        <div class="relative inline-block text-left">
                            <button id="dropdownButton" class="focus:outline-none">
                                <i class=" hover:bg-gray-200 p-2 fas fa-ellipsis rounded-full"></i>
                            </button>
                            <div id="dropdownMenu"
                                class="dropdown-content absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                                <a href="{{ route('notifications.read-all') }}"
                                    class="block px-4 py-2 rounded hover:opacity-50">
                                    <i class="fas fa-check-circle mr-2"></i> Mark as all read
                                </a>
                                <a href="{{ route('notifications.delete-all') }}"
                                    class="block px-4 py-2 rounded hover:opacity-50">
                                    <i class="fas fa-trash mr-2"></i> Delete All
                                </a>
                            </div>

                        </div>
                    </div>



                    <div id="notification-list"
                        class="z-10 flex flex-col max-h-[500px] overflow-y-auto custom-scrollbar">

                        @include('admin.partials.notification-list')

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


                <script>
                    function showMessagesLoader() {
                        document.getElementById('messages-loader').classList.remove('hidden');
                    }

                    function hideMessagesLoader() {
                        document.getElementById('messages-loader').classList.add('hidden');
                    }
                    document.body.addEventListener('htmx:afterRequest', function () {
                        hideMessagesLoader();


                    });
                </script>
                <div id="messageSection" class="relative  {{$page_title == 'Messages' ? 'hidden' : ''}}"
                    id="inside-messages" title="Messages">
                    <button onclick="showMessagesLoader()" hx-get="{{route('admin.contacts-refresh')}}"
                        hx-swap="innerHTML" hx-target="#messages-dropdown" hx-trigger="click" id="messages-icon"
                        class="flex items-center hover:opacity-50 focus:outline-none">
                        <i class="fas fa-inbox fa-lg mt-1 text-blue-600"></i>
                        <span id="messageTitle" class="ml-2">Messages</span>


                        <span id="message-count"
                            class="absolute top-0 left-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full transform -translate-x-1/2 -translate-y-1/2">
                            {{ $unreadMessages }}
                        </span>

                    </button>



                    <style>
                        @media (orientation: landscape) {
                            #messages-dropdown {
                                position: absolute;
                                right: 0;
                            }
                        }

                        @media (orientation: portrait) {
                            #messages-dropdown {
                                position: fixed;
                                left: 0;
                                right: 0;
                                top: 50px;
                            }
                        }
                    </style>

                    @include('admin.scripts.message-count')
                    <!-- Messages Dropdown Menu -->

                    <div id="messages-dropdown"
                        class="rounded mt-2 hidden mx-4 bg-white rounded-b-lg shadow-lg border border-gray-200 z-50">
                        @include('admin.partials.messages-dropdown')

                    </div>

                </div>


                <!-- User Icon -->
                <div id="inside-user" class="relative" title="Profile">
                    <button id="user-icon" class="relative flex items-center focus:outline-none hover:opacity-50">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <img class="border-2 border-gray-600 h-[40px] w-[40px] rounded-full"
                                    src="{{ Storage::exists('public/images/users/' . Auth::user()->img) ? asset('storage/images/users/' . Auth::user()->img) : asset('asset/photos/user.png') }}"
                                    alt="User Image">

                                <i
                                    class="fas fa-chevron-circle-down absolute bottom-0 right-0 transform translate-x-1 translate-y-1 text-gray-600"></i>
                            </div>
                        </div>
                    </button>



                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown" class="flex absolute right-0 hidden z-50 justify-center items-center z-50">

                        <div id="user-dropdown-content" class="w-[200px] rounded-lg shadow-xl border border-gray-300">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('profile') }}" title="{{Auth::user()->name}}">

                                    <div class="p-2 cursor-pointer hover:opacity-50 rounded-t-lg">

                                        <i class="text-blue-500 fas fa-user mr-2"></i>
                                        <span>{{ Auth::user()->name }}</span>

                                    </div>
                                </a>

                                <div class="p-2 cursor-pointer hover:opacity-50 rounded-b-lg" title="Logout"
                                    onclick="document.getElementById('logoutConfirm').classList.remove('hidden')">


                                    <i class="text-blue-500 fas fa-sign-out-alt mr-2"></i>
                                    <span>Logout</span>



                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

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

                        <button onclick="document.getElementById('logoutConfirm').classList.add('hidden')" type="button"
                            class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">
                            Cancel
                        </button>
                        <a href="{{ route('logout') }}" type="button"
                            class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Logout</a>
                    </div>

                </div>

            </div>
        </div>

        @include('admin.modals.message-new')


        <script>

            document.addEventListener('DOMContentLoaded', function () {
                const notificationIcon = document.getElementById('notification-icon');
                const userIcon = document.getElementById('user-icon');
                const messageIcon = document.getElementById('messages-icon');
                const notificationDropdown = document.getElementById('notification-dropdown');
                const messagesDropdown = document.getElementById('messages-dropdown');
                const userDropdown = document.getElementById('user-dropdown');
                const seeMoreBtn = document.getElementById('see-more-btn');
                const notificationList = document.getElementById('notification-list');

                // Toggle notification dropdown
                notificationIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                    userDropdown.classList.add('hidden'); // Close other dropdowns
                    messagesDropdown.classList.add('hidden');
                });

                // Toggle user dropdown
                userIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    notificationDropdown.classList.add('hidden');
                    messagesDropdown.classList.add('hidden');
                });

                // Toggle messages dropdown
                messageIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    messagesDropdown.classList.toggle('hidden');
                    notificationDropdown.classList.add('hidden');
                    userDropdown.classList.add('hidden');
                });

                // Prevent dropdown from closing when clicking inside
                notificationDropdown.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
                messagesDropdown.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
                userDropdown.addEventListener('click', (event) => {
                    event.stopPropagation();
                });

                // See more button functionality
                if (seeMoreBtn) {
                    seeMoreBtn.addEventListener('click', function () {
                        notificationList.classList.toggle('max-h-64');
                        seeMoreBtn.textContent = notificationList.classList.contains('max-h-64') ? 'See More' : 'See Less';
                    });
                }
                dropdownButton.addEventListener('click', (event) => {
                    event.stopPropagation();

                    dropdownMenu.classList.toggle('hidden');
                });

                // Click outside to close dropdowns
                document.addEventListener('click', function (event) {
                    const clickedElement = event.target;
                    // Close the dropdown menu
                    if (!dropdownMenu.contains(clickedElement) && !dropdownButton.contains(clickedElement)) {
                        dropdownMenu.classList.add('hidden');
                    }

                    if (!notificationDropdown.contains(clickedElement) && !notificationIcon.contains(clickedElement)) {
                        notificationDropdown.classList.add('hidden');
                    }
                    if (!userDropdown.contains(clickedElement) && !userIcon.contains(clickedElement)) {
                        userDropdown.classList.add('hidden');
                    }
                    if (!messagesDropdown.contains(clickedElement) && !messageIcon.contains(clickedElement)) {
                        messagesDropdown.classList.add('hidden');
                    }
                });


            });

        </script>