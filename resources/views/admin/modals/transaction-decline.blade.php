@foreach($transactions as $transaction)
    <div id="delete-confirmation-{{$transaction->id}}"
        class="flex fixed inset-0 items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">

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
                        Are you sure to decline this transaction?
                    </p>

                </div>

            </div>

            <div class="flex justify-end p-2 space-x-2 bg-gray-100 rounded-b">

                <button
                    onclick="document.getElementById('delete-confirmation-{{$transaction->id}}').classList.add('hidden')"
                    class="font-medium px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">
                    No, cancel.
                </button>
                <a href="{{ route('transactionDecline', ['id' => $transaction->id]) }}" id="confirm-delete-btn"
                    class="font-medium px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">
                    Yes, decline.
                </a>
            </div>
        </div>

    </div>



@endforeach