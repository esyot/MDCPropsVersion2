<div id="single-preview-{{$transaction->id}}"
    class="bg-gray-800 bg-opacity-50 fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-[35rem] max-w-full relative overflow-hidden">
        <!-- Close Button -->
        <button class="absolute top-2 text-2xl right-4 text-gray-500 hover:text-gray-700"
            onclick="document.getElementById('single-preview-{{$transaction->id}}').classList.add('hidden')">&times;</button>

        <!-- Modal Content -->
        <div class="flex flex-col">
            <!-- Image Section -->
            <div class="w-full">
                <img src="{{ asset('storage/images/categories/' . $transaction->item->category->folder_name . '/' . $transaction->item->img) }}"
                    alt="Product Image" class="w-full h-64 object-cover rounded-t-lg">
            </div>
            <h1></h1>

            <!-- Details Section -->
            <div class="w-full p-4">
                <h2 class="text-2xl font-semibold mb-4 border-b-2 border-gray-200 pb-2">Transaction Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Item Name:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->item->name }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Rentee Name:</span>
                        <p class="text-gray-900 text-base">
                            {{ $transaction->transaction->rentee->first_name }}
                            {{ $transaction->transaction->rentee->middle_name[0] }}.
                            {{ $transaction->transaction->rentee->last_name }}
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Contact #:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->transaction->rentee->contact_no }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Email:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->transaction->rentee->email }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Status:</span>
                        <p class="text-gray-900 text-base">
                            @if($transaction->approvedByAdmin_at == null && $transaction->approvedByCashier_at == null)
                                <span>pending</span>
                            @elseif($transaction->approvedByAdmin_at && $transaction->approvedByCashier_at)
                                <span>Approved</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Pickup Date:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->rent_date }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Return Date:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->rent_return }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 text-sm">Destination:</span>
                        <p class="text-gray-900 text-base">{{ $transaction->destination->municipality}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>