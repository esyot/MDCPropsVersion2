<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Dashboard</title>
</head>

<body>
    <div title="Monitor Transaction"
        class="fixed right-0 z-50 p-2 transition-transform duration-300 hover:scale-110 ease-in-out">
        <a href="{{ route('customerService') }}"> <i class="fas fa-desktop fa-xl text-white shadow-md"></i></a>
    </div>
    <div id="welcome"
        class="flex fixed inset-0 bg-gradient-to-b from-blue-500 to-blue-900 justify-center items-center z-40">
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
                <h1 class="text-2xl font-bold">Welcome!</h1>
            </section>

            @if (session()->has('rentee'))
                <div class="text-green-600 text-center">
                    <p>Welcome back! Your rentee has been created.</p>
                </div>
            @endif

            <footer class="flex justify-center p-2 mb-2">
                <a href="{{ route('getStarted') }}"
                    class="px-4 py-2 bg-blue-200 text-blue-800 rounded-lg hover:bg-blue-500 hover:text-blue-100 shadow">
                    Get Started!
                </a>
            </footer>
        </div>
    </div>
</body>

</html>