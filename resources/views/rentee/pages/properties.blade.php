<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/dist/qrious.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/main.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>

<style>
    @media(orientation:portrait) {
        #rightbar {
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
    @if(count($properties) > 0)
        <div class="flex items-center justify-between z-50 bg-gradient-to-r from-blue-500 to-blue-800 p-2 shadow-md">
            <div class="flex items-center">
                <a href="{{ route('home', ['rentee' => $rentee]) }}" class="hover:opacity-50 z-40 px-4 py-2 rounded">
                    <i class="fas fa-arrow-circle-left fa-2xl text-white"></i>
                </a>
                <h1 class="text-white font-bold line-clamp-1">{{ $properties->first()->category->title }}</h1>
            </div>
            <div id="searchContainer">
                <form hx-get="{{ route('renteeItemsFilter', ['category_id' => $category_id, 'rentee' => $rentee]) }}"
                    hx-target="#item-single" hx-swap="innerHTML" hx-trigger="input" name="search" id="searchBar"
                    onclick="expand()"
                    class="flex space-x-1 items-center bg-white border border-gray-300 rounded-full cursor-pointer">
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

        <div id="rightbar" class="fixed right-0 mt-4">
            <div class="flex flex-col items-center space-y-2">


                <a id="cart-icon" @if( $cartedProperties!=0)href="{{ route('cart', ['rentee' => $rentee]) }}" @endif
                    title="Cart" class="cursor-pointer hover:opacity-50 z-40 drop-shadow px-4 py-2 rounded mr-2">
                    @if($cartedProperties != 0)
                    <span class="absolute top-0 right-1 bg-red-500 text-white rounded-full px-[5px] text-xs">
                        {{ $cartedProperties}}
                    </span>
                    @endif
                    <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>


                </a>

                <button onclick="confirmLogoutModal()" title="Cancel Reservation"
                    class="hover:opacity-50 mb-2 z-40 drop-shadow px-4 py-2 rounded">
                    <i class="fa-solid fa-right-from-bracket fa-2xl text-blue-400"></i>
                </button>

            </div>

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

            @include('rentee.partials.property-single')

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
                <a href="{{ route('rentee.back-to-home', ['rentee' => $rentee]) }}" class="hover:underline text-blue-500">click here.</a>
            </div>

        </div>
        </div>
    @endif
    <script>
    (function () {
        const unavailableDates = {};

        async function fetchUnavailableDates(itemId) {
            try {
                const res = await fetch(`/rentee/property/${itemId}`);
                if (res.ok) {
                    const data = await res.json();
                   
                    unavailableDates[itemId] = data.map(date => convertToLocalDate(date));
                } else {
                    unavailableDates[itemId] = [];
                }
            } catch (err) {
                unavailableDates[itemId] = [];
            }
            updateCalendar(itemId); 
        }
        function convertToLocalDate(dateStr) {
            const date = new Date(dateStr); 
            const localYear = date.getFullYear();
            const localMonth = String(date.getMonth() + 1).padStart(2, '0');
            const localDay = String(date.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`; 
        }

       
        function openCalendar(itemId) {
            fetchUnavailableDates(itemId); 
            document.getElementById(`date-${itemId}`).classList.remove('hidden');
        }

      
        function changeMonth(itemId, dir) {
            const monthInput = document.getElementById(`month-input-${itemId}`);
            const date = new Date(monthInput.value + '-01');

            if (dir === 'right') date.setMonth(date.getMonth() + 1);
            if (dir === 'left') date.setMonth(date.getMonth() - 1);
            if (dir === 'today') date.setFullYear(new Date().getFullYear(), new Date().getMonth());

            monthInput.value = date.toISOString().slice(0, 7);
            updateCalendar(itemId);
        }

       
        function getLocalDate() {
            const today = new Date();
            const localYear = today.getFullYear();
            const localMonth = String(today.getMonth() + 1).padStart(2, '0'); 
            const localDay = String(today.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`;
        }

       
        function updateCalendar(itemId) {
            const [y, m] = document.getElementById(`month-input-${itemId}`).value.split('-').map(Number);
            const daysInMonth = new Date(y, m, 0).getDate();
            const calendarContainer = document.getElementById(`calendar-${itemId}`);
            const unavailableDays = new Set((unavailableDates[itemId] || [])); 

            const todayString = getLocalDate(); 

            calendarContainer.innerHTML = '';
            
            Array.from({ length: new Date(y, m - 1).getDay() }).forEach(() => calendarContainer.appendChild(document.createElement('div')));

        
            for (let day = 1; day <= daysInMonth; day++) {
                const currentDay = new Date(y, m - 1, day);
                const localDayString = getLocalDateFromDate(currentDay); 

                const dayDiv = document.createElement('div');
                dayDiv.innerText = day;
                dayDiv.className = 'flex justify-center shadow-md items-center h-10 w-10 bg-white rounded border relative'; 

                
                if (currentDay.getDay() === 0) dayDiv.classList.add('text-red-500');

               
                if (localDayString === todayString) {
                    dayDiv.classList.add();
                    
                   
                    const circle = document.createElement('i');
                    circle.className = 'fas fa-circle text-green-500 text-[5px] absolute bottom-1 z-50 left-1/2 transform -translate-x-1/2';
                    dayDiv.appendChild(circle);
                }

             
                if (unavailableDays.has(localDayString)) {
                    dayDiv.classList.add('bg-gray-300', 'text-gray-400');
                    dayDiv.style.pointerEvents = 'none';
                }

                calendarContainer.appendChild(dayDiv);
            }
        }

        function getLocalDateFromDate(date) {
            const localYear = date.getFullYear();
            const localMonth = String(date.getMonth() + 1).padStart(2, '0');
            const localDay = String(date.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`; 
        }

        document.addEventListener('DOMContentLoaded', () => {
            @foreach($properties as $property)
                updateCalendar('{{$property->id}}');
            @endforeach
        });

        window.openCalendar = openCalendar;
        window.changeMonth = changeMonth;
    })();
    
</script>


</body>

</html>