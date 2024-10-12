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

<body class="bg-gray-200">

    <header class="flex items-center bg-white p-2 space-x-1">
        <a href="{{ route('home') }}" class="transition-transform ease-in-out duration-300 hover:scale-110">
            <i class="fas fa-arrow-circle-left fa-xl text-blue-500"></i>
        </a>

        <h1 class="text-xl">
            Cart
        </h1>

    </header>

</body>

</html>