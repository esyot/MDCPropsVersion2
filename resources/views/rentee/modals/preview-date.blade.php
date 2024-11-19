<div id="date-{{$property->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-75  hidden select-none z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="text-center font-bold mb-4">
            <h2>Unavailable Dates of</h2>
            <h2 class="text-xl text-blue-600">{{ $property->name }}</h2>
        </div>
        <div class="flex space-x-3 justify-between items-center mb-4">

            <input type="month" id="month-input-{{$property->id}}"
                class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ now()->format('Y-m') }}" onchange="updateCalendar('{{$property->id}}')">

            <div class="flex items-center space-x-2">
                <button onclick="changeMonth('{{$property->id}}', 'today')"
                    class="px-4 py-2 bg-teal-500 text-teal-100 rounded hover:opacity-50">
                    <span>Today</span>
                </button>
                <button onclick="changeMonth('{{$property->id}}', 'left')" class="hover:opacity-50">
                    <i class="fas fa-chevron-circle-left text-blue-500 fa-2xl"></i>
                </button>
                <button onclick="changeMonth('{{$property->id}}', 'right')" class="hover:opacity-50">
                    <i class="fas fa-chevron-circle-right text-blue-500 fa-2xl"></i>
                </button>

            </div>

        </div>
        <div class="border border-gray-300 grid grid-cols-7 gap-1 rounded-t-lg bg-gray-200 text-center">

            <div class="font-bold text-red-500">Sun</div>
            <div class="font-bold">Mon</div>
            <div class="font-bold">Tue</div>
            <div class="font-bold">Wed</div>
            <div class="font-bold">Thu</div>
            <div class="font-bold">Fri</div>
            <div class="font-bold">Sat</div>
        </div>
        <div id="calendar-{{$property->id}}" class="grid grid-cols-7 gap-1 border border-gray-300 p-2 bg-white">

        </div>
        <div class="flex justify-center mt-2">
            <small>
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span>Unavailable days are filtered with color gray.</span>
            </small>
        </div>

        <div class="flex justify-end py-2 space-x-1">
            <button onclick="document.getElementById('date-{{$property->id}}').classList.add('hidden')"
                class="px-4 py-2 bg-gray-500 text-gray-100 hover:opacity-50 rounded">Close</button>
            <a href="{{ route('rentee.add-to-cart', ['rentee' => $rentee, 'property' => $property->id]) }}"
                class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Add to cart</a>

        </div>
    </div>
</div>