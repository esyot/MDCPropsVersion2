<div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
    <div id="modal-content"
        class="{{ $setting->transition == true ? 'animation-open' : '' }} bg-white p-6 rounded-lg shadow-lg w-[50rem] relative">

        <!-- Close Button -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold mb-4">Records on {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h2>
            @if(!$roles->contains('viewer'))
                <button
                    onclick="document.getElementById('transaction-add-{{ \Carbon\Carbon::parse($date)->format('j') }}').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-800 text-white mb-2 rounded">Add Transaction</button>
            @endif
        </div>
        <!-- Modal Content -->
        <div class="flex flex-col">


            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rent Pickup
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rent Return
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                        <tr title="Click this to preview {{$transaction->item->name}}"
                            class="cursor-pointer hover:bg-gray-300"
                            onclick="document.getElementById('single-preview-{{$transaction->id}}').classList.remove('hidden')">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->item->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->rent_date)->format('F j, Y') }}
                                {{ \Carbon\Carbon::parse($transaction->rent_time)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->rent_return)->format('F j, Y') }}
                                {{ \Carbon\Carbon::parse($transaction->rent_return_time)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($transaction->approvedByAdmin_at == null && $transaction->approvedByCashier_at == null)
                                    <span class="text-yellow-500">Pending</span>
                                @elseif($transaction->approvedByAdmin_at && $transaction->approvedByCashier_at)
                                    <span class="text-green-500">Approved</span>
                                @endif
                            </td>
                        </tr>
                        @include('admin.modals.date-view-single')
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mt-2">
            <button onclick="closeModal()"
                class="px-4 py-2 text-gray-100 bg-gray-500 hover:bg-gray-800 rounded">Close</button>
        </div>
    </div>
</div>

@if($setting->transition == true)
    <script>
        function closeModal() {

            document.getElementById('modal-content').classList.remove('animation-open');
            document.getElementById('modal-content').classList.add('animation-close');

            setTimeout(() => {
                document.getElementById('modal').classList.add('hidden');
            }, 150);


        }
    </script>
@else
    <script>
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');

        }
    </script>
@endif