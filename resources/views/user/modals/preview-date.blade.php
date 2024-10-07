<div id="date-{{$item->id}}" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-75 z-50">
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

<script>
    function changeMonth(itemId, direction) {
        const monthInput = document.getElementById(`month-input-${itemId}`);
        const [year, month] = monthInput.value.split('-').map(Number);

        // Adjust the month
        const newDate = new Date(year, month - 1 + direction, 1);
        monthInput.value = `${newDate.getFullYear()}-${String(newDate.getMonth() + 1).padStart(2, '0')}`;

        // Update the calendar with the new month
        updateCalendar(itemId);
    }

    function updateCalendar(itemId) {
        const monthInput = document.getElementById(`month-input-${itemId}`);
        const calendarContainer = document.getElementById(`calendar-${itemId}`);

        const [year, month] = monthInput.value.split('-').map(Number);
        const start = new Date(year, month - 1, 1);
        const end = new Date(year, month, 0); // Last day of the month

        // Clear the calendar
        calendarContainer.innerHTML = '';

        // Start with the first day of the week (Monday)
        const firstDayOfWeek = new Date(start);
        firstDayOfWeek.setDate(firstDayOfWeek.getDate() - (firstDayOfWeek.getDay() + 6) % 7);

        // Empty cells for the days before the first of the month
        for (let i = 0; i < (start.getDay() + 6) % 7; i++) {
            const emptyDiv = document.createElement('div');
            calendarContainer.appendChild(emptyDiv);
        }

        // Loop through each day of the month
        for (let day = 1; day <= end.getDate(); day++) {
            const currentDay = new Date(year, month - 1, day);
            const dayDiv = document.createElement('div');
            dayDiv.innerText = day;
            dayDiv.classList.add('flex', 'justify-center', 'items-center', 'h-10', 'w-10', 'rounded', 'transition', 'duration-300', 'cursor-pointer', 'border', 'border-gray-300');

            // Highlight Sundays
            if (currentDay.getDay() === 0) {
                dayDiv.classList.add('text-red-500');
            }

            // Add hover effect
            dayDiv.addEventListener('mouseenter', () => {
                dayDiv.classList.add('bg-gray-200');
            });
            dayDiv.addEventListener('mouseleave', () => {
                dayDiv.classList.remove('bg-gray-200');
            });

            calendarContainer.appendChild(dayDiv);
        }
    }

    // Initial render
    document.addEventListener('DOMContentLoaded', function () {
        updateCalendar('{{$item->id}}');
    });
</script>