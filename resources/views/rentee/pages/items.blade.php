<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Dashboard</title>
</head>

<style>
    @media(orientation:portrait) {
        #cart-icon {
            display: none;
        }

    }
</style>

<div id="confirmLogoutModal"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg w-[500px] mx-2">
        <div class="bg-red-500 rounded-t py-1">

        </div>

        <div class="flex items-center p-4 space-x-2 border-b-2">
            <div>
                <i class="fas fa-exclamation-circle fa-2xl text-red-500 "></i>
            </div>
            <div>
                <h1 class="text-2xl font-medium">Cancel Reservation</h1>

                <p>This will erase your cart items and start a new
                    transaction.</p>

            </div>

        </div>

        <div class="flex justify-end space-x-1 p-2 bg-gray-100 rounded-b">

            <button onclick="document.getElementById('confirmLogoutModal').classList.add('hidden')"
                class="px-4 py-2 border border-red-300 rounded text-red-500 hover:opacity-50">No, cancel.</button>
            <a href="{{ route('cancelOrder', ['rentee' => $rentee]) }}"
                class="px-4 py-2 bg-red-500 rounded text-red-100 hover:opacity-50">Yes, proceed.</a>
        </div>
    </div>
</div>
<script>
    function confirmLogoutModal() {
        document.getElementById('confirmLogoutModal').classList.remove('hidden');
    }
</script>

<body class="bg-white overflow-x-hidden overflow-y-auto">
    @if(session()->has('cart'))
        <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-red-500 text-white rounded-md shadow-md p-6 ">
                <h2 class="text-xl font-semibold mb-4">Error!</h2>
                <p>{{ session('cart') }}</p>
                <div class="flex justify-end mt-4">
                    <button onclick="document.getElementById('errorModal').classList.add('hidden')"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if(count($items) > 0)
        <div class="flex items-center justify-between z-50 bg-gradient-to-r from-blue-500 to-blue-800 p-2 shadow-md">
            <div class="flex items-center">
                <a href="{{ route('home', ['rentee' => $rentee]) }}" class="hover:opacity-50 z-40 px-4 py-2 rounded">
                    <i class="fas fa-arrow-circle-left fa-2xl text-white"></i>
                </a>
                <h1 class="text-white font-bold line-clamp-1">{{ $items->first()->category->title }}</h1>
            </div>
            <div id="searchContainer">
                <form hx-get="{{ route('renteeItemsFilter', ['category_id' => $category_id, 'rentee' => $rentee]) }}"
                    hx-target="#item-single" hx-swap="innerHTML" hx-trigger="input" name="search" id="searchBar"
                    onclick="expand()" class="flex space-x-1 items-center bg-white border border-gray-300 rounded-full">
                    <div class="flex items-center p-2 space-x-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" placeholder="Search Items..."
                            class="w-[100px] bg-transparent focus:outline-none hidden">
                    </div>

                </form>
            </div>

            <script>
                function toggleSearch(isOpen) {
                    const searchBar = document.getElementById('searchBar');
                    const input = searchBar.querySelector('input');
                    searchBar.classList.toggle('w-[160px]', isOpen);
                    input.classList.toggle('hidden', !isOpen);
                    if (isOpen) input.focus();
                }

                document.addEventListener('click', (event) => {
                    const searchContainer = document.getElementById('searchContainer');
                    if (!searchContainer.contains(event.target)) toggleSearch(false);

                });

                function expand() {
                    toggleSearch(true);

                }
            </script>
        </div>

        <div id="rightbar" class="flex fixed right-0 top-[80px] justify-end z-50">
            <!-- <button title="Messages"
                                                                        class="hover:opacity-50 mb-2 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                                                                        <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
                                                                    </button> -->

            <a id="cart-icon" href="{{ route('cart', ['rentee' => $rentee]) }}">
                <button title="Cart" class="hover:opacity-50 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                    <span class="absolute bottom-4 right-1 bg-red-500 text-white rounded-full px-[5px] text-xs">
                        {{ $cartedItems}}
                    </span>
                    <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>

                </button>
            </a>
        </div>


        @if(session()->has('success'))
            <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-green-500 text-white rounded-md shadow-md p-6 ">
                    <h2 class="text-xl font-semibold mb-4">Success!</h2>
                    <p>{{ session('success') }}</p>
                    <div class="flex justify-end mt-4">
                        <button onclick="document.getElementById('successModal').classList.add('hidden')"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div id="item-single" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4 mt-4 mx-2">

            @include('rentee.partials.item-single')

        </div>
        </div>

        @include('rentee.components.footer')
    @else
        <div class="flex flex-col justify-center items-center h-screen">
            <div class="border-2 border-red-500 px-2 shadow-md">

                <h1 class="text-2xl">No items available. </h1>

            </div>
            <div class="flex space-x-1">
                <p>back to home,</p>
                <a href="{{ route('home', ['rentee'=>$rentee]) }}" class="hover:underline text-blue-500">click here.</a>
            </div>

        </div>
        </div>
    @endif
    <script>
        (function () {
            const unavailableDates = {};

            async function fetchUnavailableDates(itemId) {
                try {
                    const res = await fetch(`/rentee/item/${itemId}`);
                    unavailableDates[itemId] = res.ok ? await res.json() : [];
                } catch {
                    unavailableDates[itemId] = [];
                }
                updateCalendar(itemId);
            }

            function openCalendar(itemId) {
                fetchUnavailableDates(itemId);
                document.getElementById(`date-${itemId}`).classList.remove('hidden');
            }

            function changeMonth(itemId, dir) {
                const monthInput = document.getElementById(`month-input-${itemId}`);
                const date = new Date(monthInput.value + '-01');
                date.setMonth(date.getMonth() + (dir === 'right' ? 1 : -1));
                monthInput.value = date.toISOString().slice(0, 7);
                updateCalendar(itemId);
            }

            function updateCalendar(itemId) {
                const [y, m] = document.getElementById(`month-input-${itemId}`).value.split('-').map(Number);
                const daysInMonth = new Date(y, m, 0).getDate();
                const calendarContainer = document.getElementById(`calendar-${itemId}`);
                calendarContainer.innerHTML = '';

                // Ensure unavailableDates[itemId] is an array
                const unavailableDays = new Set(Array.isArray(unavailableDates[itemId]) ? unavailableDates[itemId].map(date => date.split('T')[0]) : []);

                // Add empty divs for the leading days of the month
                Array.from({ length: new Date(y, m - 1).getDay() }).forEach(() => calendarContainer.appendChild(document.createElement('div')));

                for (let day = 1; day <= daysInMonth; day++) {
                    const currentDay = new Date(y, m - 1, day);
                    const dayDiv = document.createElement('div');
                    dayDiv.innerText = day;
                    dayDiv.className = 'flex justify-center shadow-md items-center h-10 w-10 bg-white rounded border';

                    // Check if it's a Sunday
                    if (currentDay.getDay() === 0) {
                        dayDiv.classList.add('text-red-500'); // Add red text for Sundays
                    }

                    // Check if there are no unavailable days
                    if (unavailableDays.size === 0) {
                        dayDiv.classList.add('bg-white'); // Style for available days
                    } else {
                        const isUnavailable = unavailableDays.has(new Date(currentDay.getTime() + 86400000).toISOString().split('T')[0]);
                        if (isUnavailable) {
                            dayDiv.classList.add('bg-gray-300', 'text-gray-400'); // Style for unavailable days
                        }
                    }

                    calendarContainer.appendChild(dayDiv);
                }
            }



            document.addEventListener('DOMContentLoaded', () => {
                @foreach($items as $item)
                    updateCalendar('{{$item->id}}');
                @endforeach
            });

            window.openCalendar = openCalendar;
            window.changeMonth = changeMonth;
        })();
    </script>




</body>

</html>