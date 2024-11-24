<div id="reservation-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">


    <div class="bg-white p-4 rounded">
        <div class="flex items-center">
            <span class="font-medium">Property:</span>
            <span class="ml-2 text-blue-500">{{ $reservation->property->name }}</span>
        </div>
        <div class="flex items-center">
            <span class="font-medium">Quantity:</span>
            <span class="ml-2 text-blue-500">{{ $reservation->qty }} pc/s</span>
        </div>
        <div class="flex items-center">
            <span class="font-medium">Rentee:</span>
            <span class="ml-2 text-blue-500">
                {{ $reservation->reservation->rentee->name }}</span>
        </div>
        <div class="flex items-center">
            <span class="font-medium">Contact #:</span>
            <span class="ml-2 text-blue-500">{{ $reservation->reservation->rentee->contact_no }}</span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Date Pick-up:</span>
            <span class="ml-2 text-blue-500">
                {{ \Carbon\Carbon::parse($reservation->rent_date)->format('F j, Y') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Time Pick-up:</span>
            <span class="ml-2 text-blue-500">
                {{ \Carbon\Carbon::parse($reservation->rent_time)->format('h:i A') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Date Return:</span>
            <span class="ml-2 text-blue-500">
                {{ \Carbon\Carbon::parse($reservation->rent_return)->format('F j, Y') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Time Return:</span>
            <span class="ml-2 text-blue-500">
                {{ \Carbon\Carbon::parse($reservation->rent_return_time)->format('h:i A') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Reservation Type:</span>
            <span class="ml-2 text-blue-500">
                {{ ucfirst($reservation->reservation->reservation_type) }}
            </span>

        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Purpose:</span>
            <span class="ml-2 text-blue-500">
                {{ ucfirst($reservation->reservation->purpose) }}
            </span>

        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Assigned Personel:</span>
            <span class="ml-2 text-blue-500">
                {{ $reservation->reservation->assigned_personel }}
            </span>

        </div>

        <div class="flex justify-center mt-2">
            <button type="button"
                onclick="document.getElementById('reservation-{{$reservation->id}}').classList.add('hidden')"
                class="px-4 py-2 bg-gray-500 rounded text-gray-100 hover:opacity-50">
                Close
            </button>
        </div>
    </div>
</div>