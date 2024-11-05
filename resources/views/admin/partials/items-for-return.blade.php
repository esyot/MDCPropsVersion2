@foreach ($transactions as $transaction)
    <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
        <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800">Tracking Code: {{ $transaction->tracking_code }}</h3>
            <h1 class="mt-2 text-gray-500">Rentee: {{$transaction->rentee->last_name}}, {{$transaction->rentee->first_name}}
                {{$transaction->rentee->middle_name[0]}}.
            </h1>
            <p class="mt-2 text-gray-500">Status:
                <span
                    class="font-medium {{ 
                                                                                                                                                                                                        $transaction->status === 'approved' ? 'text-green-500' :
            ($transaction->status === 'rejected' ? 'text-red-500' :
                ($transaction->status === 'in progress' ? 'text-yellow-500' :
                    'text-gray-500')) 
                                                                                                                                                                                                    }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </p>

            @if($transaction->approved_at)
                <!-- Convert approved_at to Carbon instance if it's a string -->
                <p class="mt-2 text-sm text-gray-500">
                    Approved at: {{ \Carbon\Carbon::parse($transaction->approved_at)->format('M d, Y h:i A') }}
                </p>
            @endif
        </div>
        <div class="flex justify-end bg-gray-50 p-4 border-t border-gray-200">
            <a class="text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">View Items</a>
        </div>
    </div>
@endforeach
@if(count($transactions) == 0)
    <div>
        <h1>No match found!</h1>
    </div>
@endif