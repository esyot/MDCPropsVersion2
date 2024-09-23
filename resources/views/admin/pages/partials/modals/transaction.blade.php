<div id="transaction-{{$transaction->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden">


    <div class="bg-white p-2 rounded">
        <div class="flex items-center">
            <span class="font-medium">Rentee:</span>
            <span class="ml-2 text-yellow-300">{{ $transaction->rentee_name }}</span>
        </div>
        <div class="flex items-center">
            <span class="font-medium">Contact #:</span>
            <span class="ml-2 text-yellow-300">{{ $transaction->rentee_contact_no }}</span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Date Pick-up:</span>
            <span class="ml-2 text-yellow-300">
                {{ \Carbon\Carbon::parse($transaction->rent_date)->format('F j, Y') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Time Pick-up:</span>
            <span class="ml-2 text-yellow-300">
                {{ \Carbon\Carbon::parse($transaction->rent_time)->format('h:i A') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Date Return:</span>
            <span class="ml-2 text-yellow-300">
                {{ \Carbon\Carbon::parse($transaction->rent_return)->format('F j, Y') }}
            </span>
        </div>
        <div class="flex items-center mt-1">
            <span class="font-medium">Time Return:</span>
            <span class="ml-2 text-yellow-300">
                {{ \Carbon\Carbon::parse($transaction->rent_return_time)->format('h:i A') }}
            </span>
        </div>

        <div class="flex justify-center mt-2">
            <button type="button"
                onclick="document.getElementById('transaction-{{$transaction->id}}').classList.add('hidden')"
                class="px-4 py-2 bg-gray-200 rounded text-gray-800 hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>