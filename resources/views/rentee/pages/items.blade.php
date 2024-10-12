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

<body class="bg-white overflow-x-hidden overflow-y-auto">
    @if(count($items) > 0)
        <div class="flex items-center justify-between z-50 bg-gradient-to-r from-blue-500 to-blue-800 p-2 shadow-md">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="hover:opacity-50 z-40 px-4 py-2 rounded">
                    <i class="fas fa-arrow-circle-left fa-2xl text-white"></i>
                </a>
                <h1 class="text-white font-bold line-clamp-1">{{ $items->first()->category->title }}</h1>
            </div>
            <div id="searchContainer">
                <form hx-get="{{ route('userItemsFilter', ['category_id' => $category_id]) }}" hx-target="#items"
                    hx-swap="innerHTML" hx-trigger="input" name="search" id="searchBar" onclick="expand()"
                    class="flex space-x-1 items-center bg-white p-2 border border-gray-300 rounded-full">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search Items..."
                        class="w-[80px] bg-transparent focus:outline-none hidden">

                </form>
            </div>

            <script>
                function toggleSearch(isOpen) {
                    const searchBar = document.getElementById('searchBar');
                    const input = searchBar.querySelector('input');
                    searchBar.classList.toggle('w-[140px]', isOpen);
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
            <button title="Messages" class="hover:opacity-50 mb-2 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
            </button>
            <button title="Cart" class="hover:opacity-50 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>
            </button>
        </div>

        <div class="mx-auto px-4 py-6 relative">
            <div id="items" class="flex flex-wrap -mx-2 justify-start">
                @include('rentee.partials.item')

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
                <a href="{{ route('home') }}" class="hover:underline text-blue-500">click here.</a>
            </div>

        </div>
        </div>
    @endif
    <script>
        let unavailableDates = {};

        async function fetchUnavailableDates(itemId) {
            try {
                const res = await fetch(`/rentee/item/${itemId}`);
                if (res.ok) unavailableDates[itemId] = await res.json();
                updateCalendar(itemId);
            } catch (err) {
                console.error('Fetch error:', err);
            }
        }

        function openCalendar(itemId) {
            fetchUnavailableDates(itemId);
            document.getElementById(`date-${itemId}`).classList.remove('hidden');
        }
        function changeMonth(itemId, dir) {
            const monthInput = document.getElementById(`month-input-${itemId}`);
            const [y, m] = monthInput.value.split('-').map(Number);
            let newDate;

            if (dir === 'right') {
                newDate = new Date(y, m + 1, 0);
            } else {
                newDate = new Date(y, m - 2, 1);
            }


            if (newDate.getMonth() === 12) {
                newDate.setFullYear(newDate.getFullYear() + 1);
            } else if (newDate.getMonth() === -1) {
                newDate.setFullYear(newDate.getFullYear() - 1);
            }

            monthInput.value = newDate.toISOString().slice(0, 7);
            updateCalendar(itemId);
        }

        function updateCalendar(itemId) {
            const [y, m] = document.getElementById(`month-input-${itemId}`).value.split('-').map(Number);
            const start = new Date(y, m - 1), end = new Date(y, m, 0);
            const calendarContainer = document.getElementById(`calendar-${itemId}`);
            calendarContainer.innerHTML = '';

            Array.from({ length: (start.getDay() + 6) % 7 }).forEach(() => calendarContainer.appendChild(document.createElement('div')));

            const unavailableDays = new Set();
            (unavailableDates[itemId] || []).forEach((date, i) => {
                if (i % 2 === 0) {
                    for (let d = new Date(date); d <= new Date(unavailableDates[itemId][i + 1]); d.setDate(d.getDate() + 1)) {
                        unavailableDays.add(d.toISOString().split('T')[0]);
                    }
                }
            });

            for (let day = 1; day <= end.getDate(); day++) {
                const currentDay = new Date(y, m - 1, day);
                const dayDiv = document.createElement('div');
                dayDiv.innerText = day;
                dayDiv.className = `flex justify-center items-center h-10 w-10 rounded border ${currentDay.getDay() === 0 ? 'text-red-500' : ''} ${unavailableDays.has(currentDay.toISOString().split('T')[0]) ? 'bg-gray-300' : ''}`;
                calendarContainer.appendChild(dayDiv);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            @foreach($items as $item)
                updateCalendar('{{$item->id}}');
            @endforeach
        });
    </script>

</body>

</html>