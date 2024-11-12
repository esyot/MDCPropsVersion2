<div id="modal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
    <div id="modal-content"
        class="bg-white mx-2 w-[800px] rounded shadow-md  {{ $setting->transition == true ? 'animation-open' : '' }}">
        <div class="bg-blue-500 rounded-t py-1">

        </div>
        <!-- Close Button -->
        <div class="flex items-center justify-between p-2">
            <h2 class="text-xl font-semibold mb-4">Reserved on {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h2>
            @if(!$roles->contains('viewer'))
                <button
                    onclick="document.getElementById('transaction-add-{{ \Carbon\Carbon::parse($date)->format('j') }}').classList.remove('hidden')"
                    class="font-bold px-4 py-2 text-xl bg-blue-500 hover:opacity-50 text-white mb-2 rounded">+</button>
            @endif
        </div>
        <!-- Modal Content -->
        <div class="">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="">
                            Name
                        </th>
                        <th class="">
                            Rent Pickup
                        </th>
                        <th class="">
                            Rent Return
                        </th>
                        <th class="">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach($transactions as $transaction)
                        <tr title="Click this to preview" class="text-center hover:bg-gray-200 cursor-pointer"
                            onclick="document.getElementById('single-preview-{{$transaction->id}}').classList.remove('hidden')">
                            <td class="">
                                {{ $transaction->item->name }}
                            </td>
                            <td class="">
                                {{ \Carbon\Carbon::parse($transaction->rent_date)->format('F j, Y') }}
                                {{ \Carbon\Carbon::parse($transaction->rent_time)->format('g:i A') }}
                            </td>
                            <td class="">
                                {{ \Carbon\Carbon::parse($transaction->rent_return)->format('F j, Y') }}
                                {{ \Carbon\Carbon::parse($transaction->rent_return_time)->format('g:i A') }}
                            </td>
                            <td class="">
                                @if($transaction->declinedByAdmin_at != null)
                                    <span class="text-red-500">Declined</span>
                                @elseif($transaction->approvedByAdmin_at == null)
                                    <span class="text-orange-500">Pending Admin Approval</span>
                                @elseif($transaction->canceledByRentee_at != null)
                                    <span class="text-red-500">Canceled</span>
                                @elseif($transaction->approvedByAdmin_at && $transaction->approvedByCashier_at == null && $transaction->declinedByAdmin_at == null)
                                    <span class="text-yellow-500">Pending Payment Approval</span>
                                @elseif($transaction->approvedByAdmin_at && $transaction->approvedByCashier_at && $transaction->returned_at == null)
                                    <span class="text-green-500">Approved</span>
                                @elseif($transaction->returned_at != null)
                                    <span class="text-green-500">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @include('admin.modals.date-view-single')
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end p-2 bg-gray-100 rounded-b">
            <button onclick="closeModal()"
                class="px-4 py-2 border border-gray-300 hover:opacity-50 rounded">Close</button>
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