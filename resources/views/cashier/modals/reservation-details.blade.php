<div id="reservation-details-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">
    <div class="bg-white p-2 rounded w-[500px] mx-2">
        <h1 class="text-xl font-medium">Reservation Details</h1>
        <form id="reservationForm" action="{{ route('cashier.reservation-payment') }}" method="POST">
            @csrf
            <input type="hidden" name="reservation_id" value="{{$reservation->id}}">
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
                        <li>
                            {{$item->item->name}}
                            <input type="hidden" name="itemsInArray[]" value="{{ $item->item->id }}">
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="flex p-2 justify-end space-x-2">

                <button type="button"
                    onclick="document.getElementById('reservation-details-{{$reservation->id}}').classList.add('hidden')"
                    class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">Close</button>


                <button onclick="document.getElementById('paymentConfirmationModal').classList.remove('hidden')"
                    type="button" class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Proceed to
                    Payment</button>


            </div>
    </div>
    <div id="paymentConfirmationModal"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded w-[500px]">
            <div class="py-1 bg-blue-500 rounded-t">

            </div>

            <div class="flex p-2 space-x-2 items-center border-b-2 border-gray-300">
                <i class="fa-solid fa-circle-question fa-2xl text-blue-500"></i>
                <div class="flex flex-col">
                    <h1 class="text-2xl font-medium">Confirmation</h1>
                    <span>Already received the payment?</span>

                </div>

            </div>

            <div class="flex justify-end space-x-1 p-2">
                <button onclick="document.getElementById('paymentConfirmationModal').classList.add('hidden')"
                    type="button" class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">
                    No
                </button>
                <button onclick="document.getElementById('reservationForm').submit()"
                    class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">
                    Yes
                </button>
            </div>
        </div>
        </form>
    </div>
</div>