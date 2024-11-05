@extends('admin.layouts.header')
@section('content')
<nav class="bg-blue-200 p-2 w-full shadow-md">
    <div class="flex space-x-2 justify-end">
        <form action="" class="p-2 bg-white shadow-inner rounded-full">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" class="bg-transparent focus:outline-none" placeholder="Input Tracking Code">
        </form>

        <button class="px-3 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
            <i class="fas fa-camera fa-lg"></i>
        </button>
    </div>

</nav>
<section class="container mx-auto p-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($transactions as $transaction)
            <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800">Tracking Code: {{ $transaction->tracking_code }}</h3>

                    <p class="mt-2 text-gray-500">Status:
                        <span class="font-medium {{ 
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
                <div class="bg-gray-50 p-4 border-t border-gray-200">
                    <a class="text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">View Items</a>
                </div>
            </div>
        @endforeach
    </div>
</section>

@endsection