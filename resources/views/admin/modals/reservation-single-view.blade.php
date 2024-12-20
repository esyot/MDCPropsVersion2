<div id="single-preview-background-{{$date}}-{{$reservation->property->id}}"
    class="bg-gray-800 bg-opacity-50 fixed inset-0 flex items-center justify-center hidden z-50 ">
    <div id="single-preview-content-{{$date}}-{{$reservation->property->id}}"
        class="bg-white rounded-lg shadow-lg w-[35rem] max-w-full {{$setting->transition == true ? 'animation-open' : ''}} relative overflow-hidden">
        <!-- Close Button -->
        <button class="absolute top-2 text-4xl font-bold right-4 text-gray-500 hover:text-gray-700"
            onclick="closeReservationSingleModal('{{$date}}-{{$reservation->property->id}}')">&times;</button>

        <!-- Modal Content -->
        <div class="flex flex-col">
            <!-- Image Section -->
            <div class="w-full">
                <img src="{{ asset('storage/images/categories/' . $reservation->property->category->folder_name . '/' . $reservation->property->img) }}"
                    alt="Product Image" class="w-full h-64 object-cover rounded-t-lg">
            </div>

            <!-- Details Section -->
            <div class="w-full p-4  ">
                <h2 class="text-2xl font-semibold mb-4 border-b-2 border-gray-200 pb-2">Reservation Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto h-64">
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Item Name:</span>
                        <p class="text-gray-900 text-base">{{ $reservation->property->name }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Rentee Name:</span>
                        <p class="text-gray-900 text-base">
                            {{ $reservation->reservation->rentee->name }}

                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Contact #:</span>
                        <p class="text-gray-900 text-base">{{ $reservation->reservation->rentee->contact_no }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Email:</span>
                        <p class="text-gray-900 text-base">{{ $reservation->reservation->rentee->email }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Status:</span>
                        <div class="text-gray-900 text-base">
                            @if($reservation->approvedByAdmin_at == null && $reservation->declinedByAdmin_at == null && $reservation->canceledByRentee_at == null)
                                <span class="text-yellow-500">Pending Admin Approval</span>
                            @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at == null)
                                <span class="text-orange-500">Pending Payment</span>
                            @elseif($reservation->canceledByRentee_at != null)
                                <span class="text-red-500">Canceled</span>
                            @elseif($reservation->declinedByAdmin_at != null)
                                <span class="text-red-500">Declined By Admin</span>
                            @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at == null)
                                <span class="text-orange-500">Waiting to claim</span>
                            @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at != null && $reservation->returned_at == null)
                                <span class="text-orange-500">Waiting to return</span>
                            @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at != null && $reservation->returned_at != null)
                                <span class="text-green-500">Completed</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Date Start:</span>
                        <p class="text-gray-900 text-base">
                            {{\Carbon\Carbon::parse($reservation->date_start)->format('F j, Y')}}
                            {{\Carbon\Carbon::parse($reservation->time_start)->format('h:i A')}}
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Date End:</span>
                        <p class="text-gray-900 text-base">
                            {{\Carbon\Carbon::parse($reservation->date_end)->format('F j, Y')}}
                            {{\Carbon\Carbon::parse($reservation->time_end)->format('h:i A')}}
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Destination:</span>
                        <p class="text-gray-900 text-base">{{ $reservation->destination->municipality}}</p>
                    </div>
                </div>
                <div>
                    <h1 class=" font-medium text-gray-700 text-sm mt-2">Purpose:</h1>
                    <span>{{$reservation->reservation->purpose}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function closeReservationSingleModal(Id) {

        if (@json($setting->transition)) {
            document.getElementById('single-preview-content-' + Id).classList.add('animation-close');
            setTimeout(() => {
                document.getElementById('single-preview-background-' + Id).classList.add('hidden');
                document.getElementById('single-preview-content-' + Id).classList.remove('animation-close');
            }, 150);
        } else {
            document.getElementById('single-preview-content-' + Id).classList.remove('animation-close');
            document.getElementById('single-preview-background-' + Id).classList.add('hidden');
        }
    }
</script>