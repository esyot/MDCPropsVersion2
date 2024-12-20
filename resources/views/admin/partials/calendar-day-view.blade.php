<style>
    @media(orientation:portrait) {
        #modal-content-{{$date}} {
            position: fixed;
            top: 0;
            margin-top: 20px;
        }

        #modal-table-{{$date}} {
            height: 500px;
        }
    }
</style>

<div id="modal-background-{{$date}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40">
    <div id="modal-content-{{$date}}"
        class="bg-white mx-2 rounded w-[600px] shadow-md {{$setting->transition == true ? 'animation-open' : '' }}">
        <div class="bg-blue-500 rounded-t py-1">

        </div>
        <!-- Close Button -->
        <div class="flex items-center justify-between p-2">
            <h2 class="text-xl font-semibold mb-4">Reserved on <span class="text-red-500">{{$currentDate}}</span></h2>

            <button title="Insert a reservation"
                onclick="toggleReservationForm({{\Carbon\Carbon::parse($date)->format('j')}}, {{$setting->transition}})"
                class="font-bold px-4 py-2 text-xl bg-blue-500 hover:opacity-50 text-white mb-2 rounded">+</button>

        </div>
        <!-- Modal Content -->
        <div id="modal-table-{{$date}}" class="overflow-y-auto">
            <table class="w-full">
                <thead class="sticky bg-gray-50">
                    <tr class="text-xs">
                        <th class="text-center">
                            Property
                        </th>
                        <th class="text-center">
                            Date Start
                        </th>
                        <th class="text-center">
                            Date End
                        </th>
                        <th class="text-center">
                            Type
                        </th>
                        <th class="text-center">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)

                        <tr onclick="document.getElementById('single-preview-background-{{$date}}-{{$reservation->property->id}}').classList.toggle('hidden')"
                            title="Click to preview reservation" class="cursor-pointer hover:bg-gray-300 z-50">

                            <td class="text-sm text-center">{{$reservation->property->name}}</td>
                            <td class="text-sm text-center">
                                {{\Carbon\Carbon::parse($reservation->date_start)->format('F j, Y')}}
                                {{\Carbon\Carbon::parse($reservation->time_start)->format('h:i A')}}

                            </td>
                            <td class="text-sm text-center">
                                {{\Carbon\Carbon::parse($reservation->date_end)->format('F j, Y')}}
                                {{\Carbon\Carbon::parse($reservation->time_end)->format('h:i A')}}
                            </td>
                            <td class="text-sm text-center">
                                {{ucfirst($reservation->reservation->reservation_type)}}
                            </td>
                            <td class="text-sm text-center">
                                @if($reservation->approvedByAdmin_at == null && $reservation->declinedByAdmin_at == null && $reservation->canceledByRentee_at == null)
                                    <span class="text-sm text-yellow-500">Pending Admin Approval</span>
                                @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at == null)
                                    <span class="text-sm text-orange-500">Pending Payment</span>
                                @elseif($reservation->canceledByRentee_at != null)
                                    <span class="text-sm text-red-500">Canceled</span>
                                @elseif($reservation->declinedByAdmin_at != null)
                                    <span class="text-sm text-red-500">Declined By Admin</span>
                                @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at == null)
                                    <span class="text-sm text-orange-500">Waiting to claim</span>
                                @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at != null && $reservation->returned_at == null)
                                    <span class="text-sm text-orange-500">Waiting to return</span>
                                @elseif($reservation->approvedByAdmin_at != null && $reservation->approvedByCashier_at != null && $reservation->claimed_at != null && $reservation->returned_at != null)
                                    <span class="text-sm text-green-500">Completed</span>
                                @endif
                            </td>

                            @include('admin.modals.reservation-single-view')    


                        </tr>


                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="flex justify-end p-2 bg-gray-100 rounded-b">
            <button onclick="closeCalendarDayView()"
                class="px-4 py-2 border border-gray-300 hover:opacity-50 rounded">Close</button>
        </div>
    </div>
</div>

@if($setting->transition == true)
    <script>
        function closeCalendarDayView() {

            document.getElementById('modal-content-{{$date}}').classList.add('animation-close');

            setTimeout(() => {
                document.getElementById('modal-background-{{$date}}').classList.add('hidden');
            }, 150);
        }
    </script>
@else
    <script>
        function closeCalendarDayView() {

            document.getElementById('modal-content-{{$date}}').classList.remove('animation-close');

            document.getElementById('modal-background-{{$date}}').classList.add('hidden');

            document.getElementById('modal-content-{{$date}}').classList.add('hidden');


        }
    </script>


@endif