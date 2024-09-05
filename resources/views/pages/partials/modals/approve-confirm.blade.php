<div id="transaction-confirm-{{$transaction->id}}"
    class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white shadow-md max-w-md rounded-lg px-4 py-1">

        <div class="flex space-x-6 justify-center font-semibold items-start">

            <div class="shadow-md border border-gray-300 p-2 m-2 rounded-full px-3 py-1">
                <i class="p-3 py-4 font-bold text-red-500 fa-solid fa-question"></i>
            </div>



        </div>


        <div class="flex flex-col justify-center items-center mt-2">
            Are you sure to confirm this?
            <small class="text-[10px] font-semibold text-red-500">Note: this action cannot be undone.</small>
        </div>




        <div class="flex justify-around space-x-2 my-2">
            <a href="{{ route('transactionApprove', ['id' => $transaction->id]) }}"
                class=" font-medium px-4 py-2 bg-green-300 hover:bg-green-400 text-green-800 rounded-lg">
                Yes, sure.
            </a>
            <button
                onclick="document.getElementById('transaction-confirm-{{$transaction->id}}').classList.add('hidden')"
                class="font-medium px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                No, cancel.
            </button>
        </div>
    </div>

</div>