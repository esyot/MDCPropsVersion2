<div id="reservation-details-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">
    <div class="bg-white p-2 rounded w-[500px] mx-2">
        <h1 class="text-xl font-medium">Reservation Details</h1>

        <div class="mt-2 flex flex-col border-b-2">
            <label for="" class="font-medium">Rentee:</label>
            <span>
                {{$reservation->rentee->first_name}}
                {{$reservation->rentee->middle_name[0]}}.
                {{$reservation->rentee->last_name}}
            </span>
            <h1 class="font-medium">
                Items reserved:
            </h1>
            <ul>
                @foreach ($items as $item)
                    <li>{{$item->item->name}}

                    </li>

                @endforeach
            </ul>
        </div>
        <div class="flex p-2 justify-end space-x-2">

            <button
                onclick="document.getElementById('reservation-details-{{$reservation->id}}').classList.add('hidden')"
                class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">Close</button>


            <button class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Proceed to Payment</button>
        </div>
    </div>
</div>