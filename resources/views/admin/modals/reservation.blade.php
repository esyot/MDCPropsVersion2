<div id="reservation-{{$reservation->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">


    <div class="bg-white rounded">
        <div class="flex justify-between items-center">
            <h1 class="text-lg font-medium px-2">Reservation details</h1>
            <button type="button"
                onclick="document.getElementById('reservation-{{$reservation->id}}').classList.add('hidden')"
                class="text-2xl px-2 font-bold hover:opacity-50">
                &times;
            </button>
        </div>
        <div class="p-2">


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
            <div class="flex flex-col mt-1">
                <div class="flex space-x-2 items-center ">
                    <span class="font-medium">Assigned Personel:</span>
                    <span class="ml-2 text-blue-500">


                        @if($reservation->assigned_personel != null)
                            {{ $reservation->assigned_personel }}

                        @else
                            {{ optional(optional(optional($reservation->property->category)->managedCategory)->user)->name }}


                        @endif
                    </span>


                </div>

                @if(
                        $reservation->ApprovedByAdmin_at == null && $reservation->reservation->status != 'canceled'
                        && $reservation->reservation->status != 'declined'


                    )



                                    <div>
                                        <label for="">Assign to a new personel:</label>
                                        <button
                                            onclick="document.getElementById('personel-list-{{$reservation->id}}').classList.toggle('hidden')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>

                                    <div id="personel-list-{{$reservation->id}}" class="hidden">
                                        <div class="flex flex-col">

                                            <form action="{{ route('admin.assign-personel') }}" action="GET"
                                                class="flex space-x-1 justify-between items-center">
                                                @csrf

                                                <input type="hidden" name="reservation_id" value="{{$reservation->id}}">
                                                <input type="text" name="personel" placeholder="Input Personel name"
                                                    class="p-2 block w-[180px] border border-gray-300 rounded">

                                                <button
                                                    class="px-4 py-2 bg-green-500 text-green-100 rounded hover:opacity-50">Submit</button>
                                            </form>

                                        </div>


                                    </div>
                @endif
            </div>


        </div>




    </div>
</div>