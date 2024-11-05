<!-- Sidebar -->
<style>
    @media (orientation: portrait) {
        i {
            font-size: 1.5rem;
        }
    }
</style>

<div id="sidebar"
    class="flex flex-col text-white w-20 {{ $setting->transition == true ? 'transition-all duration-[300ms] ease-in-out' : '' }} z-40">

    <div class="first:mt-3 last:mb-12 flex flex-col flex-grow justify-between relative">
        <!-- Logo and Label -->
        <div class="flex flex-col items-center relative drop-shadow-lg">
            <img class="w-12 h-12" src="{{asset('asset/photos/logo.png')}}" alt="Logo">
            <div class="flex mb-4">
                <span id="logoLabel" class="ml-4 text-sm text-center hidden">MDC - Property Rental & <br>Reservation
                    System</span>
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
                        <i
                            class="fa-solid fa-calendar-days fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="pl-2 pr-[100px] text-sm hidden">Dashboard</span>
                    </section>
                </a>
            @endcan
            @can('can view items')

                <a href="{{ route('items') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                    title="Items">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Items' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                        <i
                            class="fa-solid fa-boxes-stacked fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="pl-2 pr-[130px] text-sm hidden">Items</span>
                    </section>
                </a>
            @endcan

            @can('can manage categories')
                <a href="{{ route('categories') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                    title="Categories">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Categories' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                        <i
                            class="fa-solid fa-th-large fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="pl-2 pr-[100px] text-sm hidden">Categories</span>
                    </section>
                </a>
            @endcan
            @can('can view transactions')
                <a href="{{ route('transactions') }}"
                    class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg "
                    title="Transactions">
                    <section
                        class="flex justify-center items-center rounded-lg p-4 {{ $page_title == 'Transactions' ? 'bg-gray-100 text-blue-500 ' : '' }} ">
                        <i
                            class="fa-solid fa-business-time fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                        <span class="pl-2 pr-[100px] text-sm hidden">Transactions</span>
                    </section>
                </a>
            @endcan
            <a href="{{ route('admin.return-item') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="Users">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Return Item' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i
                        class="fas fa-truck fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Return</span>
                </section>
            </a>

            @hasrole('superadmin')
            <a href="{{ route('users') }}"
                class="flex items-center justify-center text-white hover:text-blue-300 transition duration-200 rounded-lg"
                title="Users">
                <section
                    class="flex justify-center items-center rounded-lg p-4  {{ $page_title == 'Manage Users' ? 'bg-gray-100 text-blue-500' : '' }}">
                    <i
                        class="fa-solid fa-users-gear fa-lg transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
                    <span class="pl-2 pr-[130px] text-sm hidden">Users</span>
                </section>
            </a>
            @endhasrole



        </div>

        <div class="flex justify-center">
            <button title="Expand | Shrink" id="toggle-button"
                class="px-2 py-2 flex items-center justify-center bg-white hover:bg-gray-400 rounded-full transition duration-200 transform hover:scale-110">
                <i class="fa-solid fa-arrow-right text-black"></i>
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
        toggleButton.querySelector('i').classList.toggle('fa-arrow-left');
        toggleButton.querySelector('i').classList.toggle('fa-arrow-right');
        logoLabel.classList.toggle('hidden');
        menuItems.classList.toggle('ml-12');

        const isExpanded = sidebar.classList.contains('w-64');
        [...menuItems.children].forEach(item => {
            item.classList.toggle('justify-center', !isExpanded);
            item.classList.toggle('pl-4', isExpanded);
            item.querySelector('span').classList.toggle('hidden', !isExpanded);
        });
    });
</script>