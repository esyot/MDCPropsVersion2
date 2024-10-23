<div id="transaction-confirm-{{$transaction->id}}"
    class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden z-40">
    <div class="bg-white shadow-md max-w-md rounded w-[500px]">
        <div class="bg-blue-500 py-1 w-full rounded-t">
        </div>
        <div class="flex space-x-4 p-4 border-b-2 justify-start items-center font-semibold items-start">

            <div>
                <i class="fa-solid fa-question-circle fa-2xl text-blue-500"></i>
            </div>

            <div>
                <h1 class="text-2xl">
                    Confirmation
                </h1>
                <p class="font-normal">Are you sure to confirm this transaction?</p>

            </div>
        </div>

        <div class="flex justify-end space-x-2 p-2 bg-gray-100 rounded-b">
            <button
                onclick="document.getElementById('transaction-confirm-{{$transaction->id}}').classList.add('hidden')"
                class="font-medium px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 text-gray-800 rounded">
                No, cancel.
            </button>
            <a href="{{ route('transactionApprove', ['id' => $transaction->id]) }}"
                class=" font-medium px-4 py-2 bg-blue-500 hover:opacity-50 text-blue-100 rounded">
                Yes, sure.
            </a>

        </div>
    </div>
</div>