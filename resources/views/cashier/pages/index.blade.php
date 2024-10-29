@extends('cashier.layouts.header')
@section('content')


<section>
    @if(!session()->has('cashier'))
        <div id="cashier-welcome" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
            <div class="flex justify-center flex-col bg-white w-[400px] rounded shadow-md">
                <div class="bg-blue-500 py-1 rounded-t">
                </div>
                <div class="flex p-2 items-center space-x-3 border-b-2 border-gray-300">
                    <img src="{{ asset('asset/logo/logo.png') }}" class="p-1 bg-blue-500 rounded-full w-[60px] h-[60px]"
                        alt="">
                    <div class="flex flex-col ">
                        <h1 class="text-2xl font-medium">Welcome to</h1>
                        <h1 class="text-normal">MDC Property Reservation & Rental Management System.
                        </h1>
                    </div>
                </div>
                <div class="flex p-2 justify-end bg-gray-100 rounded-b">
                    <a href="{{ route('cashier.session-start') }}" type="button"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Get
                        Started</a>
                </div>

            </div>
            </d>
    @endif
</section>

@endsection