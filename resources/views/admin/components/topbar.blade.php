<!-- Navbar -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between bg-white p-4 shadow-md relative">
        <!-- App Name -->
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
                    class="rounded absolute right-0 mt-2 hidden w-[30rem] bg-white p-2 shadow-lg border border-gray-200 z-50 scale-95 transform transition-transform transition-opacity duration-300 ease-out">
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
                                <a href="{{ route('deleteAll') }}" class="block px-4 py-2 rounded hover:bg-gray-100">
                                    <i class="text-blue-500 fas fa-trash mr-2"></i> Delete All
                                </a>
                            </div>

                        </div>
                    </div>

                    <div id="loader"
                        class="rounded  bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center z-50 hidden">

                        <img src="{{asset('asset/loader/loading.gif')}}" alt="Loading..." class="w-16 h-16">
                    </div>

                    <div id="notification-list" class="z-10 flex flex-col max-h-64 overflow-y-auto custom-scrollbar">

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
                @if($page_title != 'Messages')
                    <div class="relative" id="inside-messages" title="Messages">
                        <button id="messages-icon" class="flex items-center focus:outline-none">
                            <i class="fa-solid fa-envelope fa-lg mt-1 text-blue-600"></i>
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
                            class="absolute right-0 hidden mt-2 w-[400px] bg-white border border-gray-300 rounded-lg shadow-lg z-50">
                            <div class="p-2">
                                <div class="flex justify-between items-center">
                                    <div id="dropdown-title" class="py-2">
                                        <h1 class="text-2xl font-bold">Chats</h1>
                                    </div>


                                    <div class=" flex">
                                        <div title="Options">
                                            <i
                                                class="fa-solid fa-ellipsis hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>
                                        </div>
                                        <div title="See all in messages">
                                            <a href="{{ route('messages') }}" class=" font-medium  hover:underline py-2">


                                                <i
                                                    class="fas fa-expand-arrows-alt hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>
                                            </a>

                                        </div>
                                        <div title="New message"
                                            onclick="document.getElementById('message-new').classList.remove('hidden')">
                                            <i
                                                class="fa-solid fa-edit hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>

                                        </div>
                                    </div>

                                </div>


                                <form hx-get="{{ route('contacts') }}" hx-trigger="input" hx-swap="innerHTML"
                                    hx-target="#contact-list"
                                    class="flex justiify-around px-2 items-center bg-gray-200 rounded-full">

                                    <div class="p-2">
                                        <div class="fas fa-search text-black"></div>
                                    </div>

                                    <input type="text" name="searchValue" placeholder="Search contact"
                                        class="mr-8 focus:outline-none bg-transparent">
                                </form>
                            </div>
                            <div class="p-2 max-h-80 overflow-y-auto custom-scrollbar">

                                <ul id="contact-list" class="list-none">
                                    @include('admin.partials.contact-list')
                                </ul>



                            </div>
                            <div class="flex justify-center bg-gray-200 w-full rounded-b-lg">
                                <a href="{{ route('messages') }}"
                                    class="text-blue-500 font-medium  hover:underline py-2">See all in Messages</a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Icon -->
                <div id="inside-user" class="relative" title="Profile">
                    <button id="user-icon" class="relative flex items-center focus:outline-none">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <img class="border-2 border-gray-600 h-[40px] w-[40px] rounded-full"
                                    src="{{ asset('storage/images/users/' . Auth::user()->img) }}" alt="User Image">
                                <i
                                    class="fas fa-chevron-circle-down absolute bottom-0 right-0 transform translate-x-1 translate-y-1 text-gray-600"></i>
                            </div>
                        </div>
                    </button>



                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown"
                        class="absolute right-0 hidden mt-2 border p-2 border-gray-300 bg-white rounded-lg w-[200px] shadow-xl z-10">
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('profile') }}">

                                <div class="p-2 cursor-pointer hover:bg-gray-200 rounded-lg">

                                    <i class="text-blue-500 fas fa-user mr-2"></i>
                                    <span>{{ Auth::user()->name }}</span>

                                </div>
                            </a>

                            <div class="p-2 cursor-pointer hover:bg-gray-200 rounded-lg"
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
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">

            <div class="flex flex-col bg-white p-4 rounded space-y-2 items-center">
                <div class="bg-red-500 px-3 rounded-full py-1">
                    <i class="fa-solid fa-question text-red-100"></i>

                </div>
                <div>
                    <h1 class="text-xl font-medium">Are you sure to log-out?</h1>


                </div>
                <div clas="flex justify-center">

                    <a href="{{ route('logout') }}" type="button"
                        class="px-4 py-2 bg-green-100 text-green-500 rounded hover:bg-green-800">Yes, sure</a>
                    <button onclick="document.getElementById('logoutConfirm').classList.add('hidden')" type="button"
                        class="px-4 py-2 bg-gray-100 text-gray-500 rounded hover:bg-gray-800">No, cancel</button>
                </div>

            </div>

        </div>

        @include('admin.modals.message-new')