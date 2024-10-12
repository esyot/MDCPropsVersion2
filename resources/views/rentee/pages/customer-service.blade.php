<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">

    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Customer Service</title>
</head>

<body class="bg-gray-200 overflow-hidden">

    <header>

        <div class="flex items-center space-x-2 p-2">
            <a href="{{ route('welcome') }}"
                class="hover:text-blue-800 text-blue-500 transition-transform duration-300 ease-in-out hover:scale-110">
                <i class="fas fa-arrow-circle-left fa-2xl"></i>
            </a>
            <h1 class="text-xl">Customer Service</h1>
        </div>

    </header>

    <section class="mt-2 flex justify-center">
        <div class="flex items-center space-x-1 bg-white px-4 py-2 rounded-full shadow-md w-96">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" class="bg-transparent focus:outline-none w-full" placeholder="Input tracking no.">
            <button class="fas fa-arrow-circle-up fa-lg text-gray-500 hover:text-gray-800"></button>
        </div>
    </section>

    <section class="">
        <div class="mt-2 flex justify-center">
            <div class="shadow-md bg-white w-[500px] px-4">
                <h1 class="">No results found.</h1>

            </div>

        </div>
    </section>

    <footer class="flex fixed bottom-0 left-0 right-0 justify-center">
        <div class="p-2">
            <p>All rights reserved &copy; 2024</p>
        </div>
    </footer>

</body>

</html>