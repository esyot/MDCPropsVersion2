@extends('cashier.layouts.header')
@section('content')


<section>
    @if(!session()->has('cashier'))
        <div id="welcome" class="flex fixed inset-0 bg-gray-800 bg-opacity-50 justify-center items-center z-40">
            <div class="bg-white rounded shadow-2xl p-2">
                <header class="flex flex-col items-center">
                    <div class="mt-2">
                        <img src="{{ asset('asset/logo/logo.png') }}"
                            class="p-1 border-4 border-blue-300 rounded-full shadow-md h-32" alt="">
                    </div>
                    <div class="flex p-2 flex-col justify-center items-center">
                        <h1 class="text-4xl font-bold text-blue-500">MDC PropRentals</h1>
                        <small>"Avail, Rent & Return."</small>
                    </div>
                </header>
                <section class="flex p-2 justify-center">

                </section>

                <div class="text-green-600 text-center">

                    <h1 class="text-2xl font-bold">Welcome {{ Auth::user()->name }}!</h1>

                    </p>
                </div>

                <footer class="flex justify-center p-2 mb-2">
                    <a href="{{ route('cashier.session-start') }}" type="button"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Get
                        Started</a>
                </footer>
            </div>
        </div>
    @endif
</section>
<section>
    <div title=" Click here to redirect"
        class="flex flex-col items-center cursor-pointer  m-4 p-4 bg-gray-400 w-[200px] rounded-lg shadow-lg transition-transform transform hover:scale-105">
        <h1 class="text-center text-lg font-semibold text-white mb-2">No. of Pending Reservations:</h1>
        <span class="text-3xl font-bold text-white">
            {{ $reservationsPending }}
        </span>

    </div>

</section>

@endsection