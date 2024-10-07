<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Dashboard</title>
    <style>
        /* Add your styles here */
    </style>
</head>

<body class="bg-white overflow-x-hidden overflow-y-auto">
    <div class="flex items-center justify-between z-50 bg-gradient-to-r from-blue-500 to-blue-800 p-2 shadow-md">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="hover:opacity-50 z-40 px-4 py-2 rounded">
                <i class="fas fa-arrow-circle-left fa-2xl text-white"></i>
            </a>
            <h1 class="text-white font-bold line-clamp-1">{{ $items->first()->category->title }}</h1>
        </div>
        <div class="relative">
            <div id="searchBar" onclick="expand()"
                class="flex space-x-1 items-center bg-white p-2 border border-gray-300 rounded-full">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Items..."
                    class="w-[100px] bg-transparent focus:outline-none hidden">
            </div>
        </div>
        <script>
            function expand() {
                const searchBar = document.getElementById('searchBar');
                searchBar.classList.add('w-[140px]');
                const input = searchBar.querySelector('input');
                input.classList.remove('hidden');
                input.focus();
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
        <div class="flex flex-wrap -mx-2 justify-start">
            @foreach($items as $item)
                <div onclick="openCalendar({{ $item->id }})"
                    class="flex flex-col justify-between h-full w-1/3 sm:w-1/4 md:w-1/5 lg:w-1/6 px-2 mt-4 transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50 cursor-pointer">
                    <div class="shadow-lg rounded-lg overflow-hidden relative">
                        <div class="w-full h-0 pt-[50%] relative">
                            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                alt="Image" class="absolute top-0 left-0 w-full h-full object-contain z-0">
                        </div>
                        <div class="bg-blue-500 text-blue-100 p-2 flex flex-col justify-center text-center relative z-10"
                            style="height: 60px;">
                            <h2 class="font-semibold text-[calc(1.5rem + 1vw)] leading-[1] max-w-full break-words">
                                {{ $item->name }}
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Date Available Preview -->
                <div id="date-{{$item->id}}"
                    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-75 hidden z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                        <div class="text-center font-bold mb-4">
                            <h2>Available Dates of</h2>
                            <h2 class="text-xl text-blue-600">{{ $item->name }}</h2>
                        </div>
                        <div class="flex space-x-3 justify-center items-center mb-4">
                            <div class="space-x-2">
                                <button onclick="changeMonth('{{$item->id}}', -1)">
                                    <i class="fas fa-chevron-circle-left text-blue-500 fa-xl"></i>
                                </button>
                                <button onclick="changeMonth('{{$item->id}}', 1)">
                                    <i class="fas fa-chevron-circle-right text-blue-500 fa-xl"></i>
                                </button>
                            </div>
                            <input type="month" id="month-input-{{$item->id}}"
                                class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="2024-10" onchange="updateCalendar('{{$item->id}}')">
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                            <div class="font-bold">Mon</div>
                            <div class="font-bold">Tue</div>
                            <div class="font-bold">Wed</div>
                            <div class="font-bold">Thu</div>
                            <div class="font-bold">Fri</div>
                            <div class="font-bold">Sat</div>
                            <div class="font-bold text-red-500">Sun</div>
                        </div>
                        <div id="calendar-{{$item->id}}" class="grid grid-cols-7 gap-1">
                            <!-- Calendar will be generated here -->
                        </div>

                        <div class="flex justify-end p-2 space-x-2">
                            <button class="px-4 py-2 bg-blue-100 text-blue-800 rounded">Add to cart</button>
                            <button onclick="document.getElementById('date-{{$item->id}}').classList.add('hidden')"
                                class="px-4 py-2 bg-red-100 text-red-800 rounded">Cancel</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        let unavailableDates = {};

        async function fetchUnavailableDates(itemId) {
            try {
                const res = await fetch(`/item/${itemId}`);
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
            monthInput.value = new Date(y, m - 1 + dir).toISOString().slice(0, 7);
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