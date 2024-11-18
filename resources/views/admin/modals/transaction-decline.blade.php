@foreach($transactions as $transaction)
    <form action="{{ route('transactionDecline', ['id' => $transaction->id]) }}" method="POST"
        id="delete-confirmation-{{$transaction->id}}"
        class="flex fixed inset-0 items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
        @csrf

        <div class="bg-white rounded w-[500px]">
            <div class="bg-red-500 py-1 rounded-t"></div>
            <div class="flex items-center p-4 space-x-2 border-b-2">
                <div class="bg-red-500 py-2 px-3 rounded-full">
                    <i class="fa-solid fa-calendar-xmark text-white"></i>
                </div>
                <div class="flex flex-col items-start">
                    <h1 class="text-2xl font-medium">
                        Decline
                    </h1>
                    <p class="font-normal">
                        Are you sure to decline this reservation?
                    </p>
                </div>


            </div>

            <div class="p-2">
                @php
                    $formattedRentDate = \Carbon\Carbon::parse($transaction->rent_date)->format('l, F j, Y');
                    $formattedReturnDate = \Carbon\Carbon::parse($transaction->rent_return)->format('l, F j, Y');
                    $formattedRentReturnTime = \Carbon\Carbon::parse($transaction->rent_return_time)->format('h:i A');
                    $formattedRentTime = \Carbon\Carbon::parse($transaction->rent_time)->format('h:i A');
                @endphp

                <p><strong>{{ $transaction->transaction->rentee->name }}</strong> reserves
                    <strong>{{ $transaction->qty }}</strong> item(s) <strong>"{{ $transaction->item->name }}"</strong> from
                    <strong>{{ $formattedRentDate }} {{$formattedRentTime}}</strong> and will return it by
                    <strong>{{ $formattedReturnDate }} {{ $formattedRentReturnTime }}</strong>.
                </p>
            </div>

            <div class="p-2">
                <h1>Message:</h1>
                <textarea name="message" class="border-2 border-gray-200 w-full" placeholder="Input Message here..."
                    required></textarea>
            </div>

            <div class="flex justify-end p-2 space-x-2 bg-gray-100 rounded-b">

                <button type="button"
                    onclick="document.getElementById('delete-confirmation-{{$transaction->id}}').classList.add('hidden')"
                    class="font-medium px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">
                    No, cancel.
                </button>
                <button type="submit" id="confirm-delete-btn"
                    class="font-medium px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">
                    Yes, decline.
                </button>
            </div>
        </div>

    </form>



@endforeach