<!-- Reservation Details Modal -->
<div id="reservation-details-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40">
    <div class="bg-white p-6 rounded-lg w-[500px] mx-4 shadow-xl">
        <h1 class="text-xl font-semibold mb-6">Reservation Details</h1>

        <!-- Reservation Form -->
        <form id="reservationForm-{{$reservation->id}}" action="{{ route('cashier.reservation-payment') }}"
            method="POST">
            @csrf
            <input type="hidden" name="reservation_id" value="{{$reservation->id}}">

            <!-- Reservation Information (Non-Table Layout) -->
            <div class="mb-4">
                <div class="flex justify-between mb-3">
                    <div class="font-medium">Rentee:</div>
                    <div>{{$reservation->rentee->name}}</div>
                </div>

                <div class="flex justify-between flex-col  mb-3">
                    <div class="font-medium">Items Reserved:</div>
                    <div>
                        <div class="flex flex-wrap w-full">
                            @foreach ($propertyReservations as $propertyReservation)
                                <div
                                    class="w-[calc(50%-1.5rem)] sm:w-[calc(33%-1.5rem)] lg:w-[calc(25%-1.5rem)] p-4 border border-gray-200 rounded-md">
                                    <div class="font-semibold">{{$propertyReservation->property->name}}</div>
                                    <div class="text-sm">Price: {{$propertyReservation->property->price}} per
                                        {{$propertyReservation->property->per}}
                                    </div>

                                    <input type="hidden" name="itemsInArray[]"
                                        value="{{ $propertyReservation->property->id }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="text-sm">Destination: {{$propertyReservation->destination->municipality}}
                        </div>
                        <div class="text-sm">Distance: {{$propertyReservation->destination->kilometers}} km
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mb-3">
                    <div class="font-medium">
                        @if($days != 0)
                            Days Renting: {{$days}} day/s
                        @else
                            Hours Renting: {{$hours}} hr/s
                        @endif
                    </div>
                    <div class="font-medium">Payment Total:</div>
                    <div class="">{{$paymentTotal ?? '[Total amount here]'}}</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
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

<!-- Payment Confirmation Modal -->
<div id="paymentConfirmationModal-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg w-[500px] shadow-xl">
        <div class="py-3 bg-blue-500 rounded-t-md text-white text-center">
            <h2 class="text-lg font-semibold">Payment Confirmation</h2>
        </div>

        <div class="flex p-4 items-center border-b-2 border-gray-300">
            <i class="fa-solid fa-circle-question fa-2xl text-blue-500 mr-4"></i>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Have you already received the payment?</p>
            </div>
        </div>

        <div class="flex justify-end space-x-4 p-4">
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