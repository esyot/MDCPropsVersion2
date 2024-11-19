<div id="reservation-details-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40">
    <div class="bg-white p-4 rounded w-[500px] mx-4 shadow-lg">
        <h1 class="text-xl font-semibold mb-4">Reservation Details</h1>

        <form id="reservationForm-{{$reservation->id}}" action="{{ route('cashier.reservation-payment') }}"
            method="POST">
            @csrf
            <input type="hidden" name="reservation_id" value="{{$reservation->id}}">

            <table class="mt-2 w-full border-collapse">
                <tr>
                    <td class="font-medium py-2">Rentee:</td>
                    <td class="py-2">{{$reservation->rentee->name}}</td>
                </tr>
                <tr>
                    <td class="font-medium py-2">Items Reserved:</td>
                    <td class="py-2">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($propertyReservations as $propertyReservation)
                                <li>{{$propertyReservation->property->name}}
                                    <input type="hidden" name="itemsInArray[]"
                                        value="{{ $propertyReservation->property->id }}">
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Days: {{$days}}</td>
                    <td class="font-medium py-2">Payment Total:</td>
                    <td class="py-2"></td>
                </tr>
            </table>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button"
                    onclick="document.getElementById('reservation-details-{{$reservation->id}}').classList.add('hidden')"
                    class="px-4 py-2 border border-blue-300 text-blue-500 hover:bg-blue-100 rounded-md">
                    Close
                </button>

                <button
                    onclick="document.getElementById('paymentConfirmationModal-{{$reservation->id}}').classList.remove('hidden')"
                    type="button" class="px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>

<div id="paymentConfirmationModal-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded w-[500px] shadow-lg">
        <div class="py-2 bg-blue-500 rounded-t-md text-white text-center">
            <h2 class="text-lg font-semibold">Payment Confirmation</h2>
        </div>

        <div class="flex p-4 space-x-4 items-center border-b-2 border-gray-300">
            <i class="fa-solid fa-circle-question fa-2xl text-blue-500"></i>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Have you already received the payment?</p>
            </div>
        </div>

        <div class="flex justify-end space-x-2 p-4">
            <button
                onclick="document.getElementById('paymentConfirmationModal-{{$reservation->id}}').classList.add('hidden')"
                type="button" class="px-4 py-2 border border-blue-300 text-blue-500 hover:bg-blue-100 rounded-md">
                No
            </button>
            <button onclick="document.getElementById('reservationForm-{{$reservation->id}}').submit()"
                class="px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
                Yes
            </button>
        </div>
    </div>
</div>