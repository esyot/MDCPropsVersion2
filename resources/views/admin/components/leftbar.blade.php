<!-- Sidebar -->
<style>
    @media (orientation: portrait) {
        i {
            font-size: 1.5rem;
        }
    }
</style>

<div id="sidebar"
    class="flex flex-col text-white w-20 {{ $setting->transition == true ? 'transition-all duration-[300ms] ease-in-out' : '' }} z-40 shadow-md">

    <div class="first:mt-3 last:mb-12 flex flex-col flex-grow justify-between relative">
        <!-- Logo and Label -->
        <div class="flex flex-col items-center relative drop-shadow-lg">
            <img class="w-12 h-12" src="{{asset('asset/logo/logo.png')}}" alt="Logo">
            <div class="flex mb-4">
                <span id="logoLabel" class="ml-4 text-sm text-center hidden">MDC Property Reservation
                    <br> Management System</span>
                <span id="logoLabel2" class="text-sm text-center mt-2">MDC PRMS</span>
            </div>
        </div>

        <!-- Menu Items -->
        <div id="menu-items" class="flex space-y-4 flex-col justify-between relative overflow-hidden">
            @can('can view dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                    title="Dashboard">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Dashboard' ? 'bg-gray-100 text-blue-500 ' : '' }}  ">
                        <i class="fa-solid fa-calendar-days fa-lg"></i>
                        <span class="pl-2 pr-[100px] text-sm hidden">Dashboard</span>
                    </section>
                </a>
            @endcan
            @can('can view properties')

                <a href="{{ route('admin.properties') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 rounded-lg "
                    title="Manage Items">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Items' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                        <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                        <span class="pl-2 pr-[130px] text-sm hidden">Items</span>
                    </section>
                </a>
            @endcan

            @can('can manage categories')
                <a href="{{ route('admin.categories') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                    title="Manage Categories">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Categories' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                        <i class="fa-solid fa-th-large fa-lg"></i>
                        <span class="pl-2 pr-[100px] text-sm hidden">Categories</span>
                    </section>
                </a>
            @endcan
            @hasrole('superadmin|admin')
            <a href="{{ route('admin.reservations') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                title="Manage Reservations">
                <section
                    class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Reservations' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                    <i class="fa-solid fa-business-time fa-lg"></i>
                    <span class="pl-2 pr-[100px] text-sm hidden">Reservations</span>
                </section>
            </a>
            @endhasrole
            @if ($currentCategory)


                @hasrole('staff')
                @if ($currentCategory->approval_level == 'staff' || $currentCategory->approval_level == 'both')
                    <a href="{{ route('admin.reservations') }}"
                        class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                        title="Manage Reservations">
                        <section
                            class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Reservations' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                            <i class="fa-solid fa-business-time fa-lg"></i>
                            <span class="pl-2 pr-[100px] text-sm hidden">Reservations</span>
                        </section>
                    </a>
                @endif
                @endhasrole
            @endif

            @hasrole('staff')
            <a href="{{ route('admin.claim-properties') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="Claim Properties">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Claim Items' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i class="fas fa-hands fa-lg"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Claim</span>
                </section>
            </a>


            <a href="{{ route('admin.return-properties') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="Return Properties">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Return Item' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i class="fas fa-truck fa-lg"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Return</span>
                </section>
            </a>
            @endhasrole


            @hasrole('superadmin|admin')
            <a href="{{ route('users') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="manage Users">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Manage Users' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i class="fa-solid fa-users-gear fa-lg"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Users</span>
                </section>
            </a>
            @endhasrole
            @hasrole('admin|superadmin')
            <a href="{{ route('admin.analytics-index') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="Analytics">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Analytics' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i class="fas fa-chart-line fa-lg"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Analytics</span>
                </section>
            </a>
            @endhasrole


        </div>

        <div class="flex justify-center">
            <button title="Expand | Shrink" id="toggle-button"
                class="hover:opacity-50 px-2 py-2 flex items-center justify-center">
                <i class="fas fa-chevron-circle-right text-[30px] text-white"></i>
            </button>
        </div>
    </div>
</div>


<script>
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
        toggleButton.querySelector('i').classList.toggle('fa-chevron-circle-left');
        toggleButton.querySelector('i').classList.toggle('fa-chevron-circle-right');
        logoLabel.classList.toggle('hidden');
        menuItems.classList.toggle('ml-12');

        const isExpanded = sidebar.classList.contains('w-64');
        [...menuItems.children].forEach(item => {
            item.classList.toggle('justify-center', !isExpanded);
            item.classList.toggle('pl-4', isExpanded);
            item.querySelector('span').classList.toggle('hidden', !isExpanded);
            document.getElementById('logoLabel').classList.toggle('hidden', !isExpanded);
            document.getElementById('logoLabel2').classList.toggle('hidden', isExpanded);
        });
    });
</script>