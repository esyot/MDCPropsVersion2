@extends('admin.layouts.header')
@section('content')

@if ($categoriesIsNull == false)

    <div id="transactions-header" class="flex items-center justify-between p-4 shadow-md">
        <div class="flex items-center space-x-2 ">
            <form onchange="this.submit()" action="{{ route('transactionsFilter') }}" class="flex justify-around space-x-4"
                method="GET">
                @csrf

                <select title="Category" name="category"
                    class="shadow-inner block px-4 py-2 border border-gray-500 rounded cursor-pointer">

                    <option value="{{ $currentCategory->id }}">{{$currentCategory->title }}</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>

                <select title="Status" name="status" id="" class="block p-2 border border-gray-500 rounded cursor-pointer">
                    <option value="{{ $currentStatus }}" class="text-red-500">{{ ucfirst($currentStatus) }}</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="declined">Declined</option>
                </select>

            </form>
        </div>
    </div>

    <div id="main-content" class="flex-1 overflow-y-auto custom-scrollbar w-full h-full">
        <div class="flex p-4">
            @foreach($transactions as $transaction)

                <div title="Details"
                    class="mx-2 w-64 bg-white rounded-lg shadow-md overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-90' : '' }}">
                    <div onclick="document.getElementById('transaction-{{$transaction->id}}').classList.remove('hidden')">


                        <img src="{{ asset('storage/images/categories/' . $currentCategory->folder_name . '/' . $transaction->item->img) }}"
                            alt="{{ $transaction->item->name }}" class="w-80 h-48 object-cover">

                    </div>
                    <div class="flex flex-col flex-wrap p-4 bg-blue-500 text-white">
                        <div class="flex flex-col justify-center">
                            <div class="flex justify-center text-xl font-semibold">{{ $transaction->item->name }}</div>
                            <div class="flex items-center">
                                <span class="font-medium">Rentee:</span>
                                <span class="ml-2 text-yellow-300">
                                    {{ $transaction->transaction->rentee->first_name }}
                                    {{ $transaction->transaction->rentee->middle_name[0] }}.
                                    {{ $transaction->transaction->rentee->last_name }}

                                </span>
                            </div>

                        </div>
                        <div class="flex justify-center space-x-2 mt-2">
                            @can('can approve transactions')
                                @if($transaction->transaction->status == 'pending')
                                    <button type=" button"
                                        onclick="document.getElementById('transaction-confirm-{{ $transaction->id }}').classList.remove('hidden')"
                                        class="shadow px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center">
                                        <i class="fas fa-check mr-2"></i> Approve
                                    </button>
                                @endif
                                <button
                                    onclick="document.getElementById('delete-confirmation-{{$transaction->id}}').classList.remove('hidden')"
                                    class="shadow px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
                                    <i class="fas fa-times mr-2"></i> Decline
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                @include('admin.modals.transaction')
                @include('admin.modals.approve-confirm')
            @endforeach
        </div>
    </div>

    @foreach($transactions as $transaction)
        <div id="delete-confirmation-{{$transaction->id}}"
            class="hidden fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 confirm-dialog">
            <div class="relative px-4 min-h-screen md:flex md:items-center md:justify-center">
                <div
                    class="bg-white p-6 rounded-lg md:max-w-md md:mx-auto p-4 fixed inset-x-0 bottom-0 z-50 mb-4 mx-4 md:relative shadow-lg">
                    <div class="items-center">
                        <div
                            class="rounded-full border border-gray-300 flex items-center justify-center w-16 h-16 flex-shrink-0 mx-auto">
                            <i class="fa-solid fa-calendar-xmark text-red-500"></i>
                        </div>

                        <div class="flex justify-center flex-col">
                            <p class="font-bold text-lg text-center">Decline Transaction</p>

                            <div class="flex justify-center mt-4">
                                <div class="text-left flex flex-col">
                                    <div class="flex items-center">
                                        <span class="font-medium">Rentee:</span>
                                        <span class="ml-2 text-red-500">{{ $transaction->rentee_name }}</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <span class="font-medium">Contact #:</span>
                                        <span class="ml-2 text-red-500">{{ $transaction->rentee_contact_no }}</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <span class="font-medium">Date Pick-up:</span>
                                        <span class="ml-2 text-red-500">
                                            {{ \Carbon\Carbon::parse($transaction->rent_date . ' ' . $transaction->rent_time)->format('F j, Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <span class="font-medium">Date Return:</span>
                                        <span class="ml-2 text-red-500">
                                            {{ \Carbon\Carbon::parse($transaction->rent_return . ' ' . $transaction->rent_return_time)->format('F j, Y h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <p class="text-sm text-gray-700 mt-4 text-center">
                                Note: This action cannot be undone.
                            </p>

                            <div class="flex justify-center m-2 space-x-2">
                                <a href="{{ route('transactionDecline', ['id' => $transaction->id]) }}" id="confirm-delete-btn"
                                    class="hover:bg-red-400 w-full md:w-auto px-4 py-3 md:py-2 bg-red-200 text-red-700 rounded-lg font-semibold text-sm md:ml-2">
                                    Yes, decline.
                                </a>
                                <button
                                    onclick="document.getElementById('delete-confirmation-{{$transaction->id}}').classList.add('hidden')"
                                    class="hover:bg-gray-400 w-full md:w-auto px-4 py-3 md:py-2 bg-gray-200 rounded-lg font-semibold text-sm mt-4 md:mt-0">
                                    No, cancel.
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach

@else

    @include('admin.partials.errors.category-null-error')

@endif

@endsection