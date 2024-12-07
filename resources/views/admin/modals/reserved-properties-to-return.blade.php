<div class="bg-white rounded shadow-md">
    <div class="bg-blue-500 py-1 rounded-t"></div>
    <h1 class="text-2xl font-bold text-left p-2">Reserved Properties</h1>
    <div class="flex border-b">

        @foreach ($reservations as $reservation)
            <div class="w-full border border-gray-300 rounded m-2 p-4 shadow-md">
                <div class="mb-3">
                    <h3 class="text-xl font-semibold ">{{ $reservation->property->name }}</h3>
                    <small>{{$reservation->qty}} pc/s</small>

                </div>


                <div class="flex items-center mb-4 space-x-2">
                    @if($reservation->returned_at != null)
                        <span class="text-green-500 font-medium">Returned</span>
                        <i class="fas fa-check-circle text-green-500"></i>
                    @elseif($reservation->returned_at == null)
                        <span class="text-orange-500 font-medium">Not yet Returned</span>
                        <i class="fas fa-hourglass text-orange-500"></i>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

    <div class="flex justify-end space-x-1 p-2 bg-gray-100 rounded-b">
        <button
            onclick="document.getElementById('reserved-properties-to-return-modal-{{$reservation_id}}').classList.add('hidden')"
            class="px-4 p-2 border border-blue-300 hover:opacity-50 text-blue-500 rounded">
            Close
        </button>
        @if($reservation->returned_at == null)
                <a href="{{ route('admin.reserved-properties-returned', [
                'reservation_id' => $reservation_id,
                'category' => $category
            ]) }}" class="bg-blue-500 text-white px-4 py-2 hover:opacity-50 rounded">
                    Mark as Returned
                </a>
        @endif
    </div>
</div>