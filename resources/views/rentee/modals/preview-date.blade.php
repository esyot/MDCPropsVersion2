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
                <button onclick="changeMonth('{{$item->id}}', 'left')">
                    <i class="fas fa-chevron-circle-left text-blue-500 fa-xl"></i>
                </button>
                <button onclick="changeMonth('{{$item->id}}', 'right')">
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
            <a href="{{ route('addToCart', ['rentee' => $rentee, 'item' => $item->id]) }}"
                class="px-4 py-2 bg-blue-100 text-blue-800 rounded">Add to cart</a>
            <button onclick="document.getElementById('date-{{$item->id}}').classList.add('hidden')"
                class="px-4 py-2 bg-red-100 text-red-800 rounded">Cancel</button>
        </div>
    </div>
</div>