@foreach ($reservations as $reservation)
    <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
        <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800">Tracking Code: {{ $reservation->tracking_code }}</h3>
            <h1 class="mt-2 text-gray-500">Rentee: {{$reservation->rentee->name}}.
            </h1>
            <p class="mt-2 text-gray-500">Status:
                @if ($reservation->status === 'approved')
                    <span class="font-medium text-green-500">
                @elseif ($reservation->status === 'rejected')
                    <span class="font-medium text-red-500">
                @elseif ($reservation->status === 'in progress')
                    <span class="font-medium text-yellow-500">
                @else
                    <span class="font-medium text-gray-500">
                @endif
                                {{ ucfirst($reservation->status) }}
                            </span>
            </p>

            @if($reservation->approved_at)
                <!-- Convert approved_at to Carbon instance if it's a string -->
                <p class="mt-2 text-sm text-gray-500">
                    Approved at: {{ \Carbon\Carbon::parse($reservation->approved_at)->format('M d, Y h:i A') }}
                </p>
            @endif
        </div>
        <div class="flex justify-end bg-gray-50 p-4 border-t border-gray-200">
            <button
                onclick="document.getElementById('reserved-properties-to-claim-modal-{{$reservation->id}}').classList.remove('hidden')"
                hx-get="{{ route('admin.reserved-properties-to-claim', ['reservation_id' => $reservation->id]) }}"
                hx-swap="innerHTML" hx-trigger="click" hx-target="#reserved-properties-to-claim-modal-{{$reservation->id}}"
                class="text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">View
                Items
            </button>
        </div>
    </div>
    <div id="reserved-properties-to-claim-modal-{{$reservation->id}}"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    </div>
@endforeach
@if(count($reservations) == 0)
    <div>
        <h1>No match found!</h1>
    </div>
@endif