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

    <header class="flex items-center p-4 space-x-2 bg-blue-500 shadow-md">
        <a href="{{ route('backToHome', ['rentee' => $rentee]) }}" class="hover:opacity-50">
            <i class="fas fa-arrow-circle-left fa-xl text-white"></i>
        </a>

        <h1 class="text-xl text-white font-bold">
            Checkout
        </h1>
    </header>
    <section class="mt-2 overflow-y-auto space-y-2 p-4">
        @foreach ($items as $item)
            <div class="flex p-4 bg-white justify-between items-center">
                <div class="flex items-center w-1/3">
                    <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                        alt="{{ $item->name }}" class="w-[50px] h-[50px] object-cover mr-2">
                    <p>{{$item->name}}</p>
                </div>

                <div class="flex items-center w-1/3 space-x-4">
                    <div>
                        <label>Quantity:</label>
                        <input type="number" class="p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label>Date Rent:</label>
                        <input type="date" class="p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label>Time Rent:</label>
                        <input type="time" class="p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label>Date Return:</label>
                        <input type="date" class="p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label>Time Return:</label>
                        <input type="time" class="p-2 border border-gray-300 rounded">
                    </div>
                </div>

                <div class="w-1/3 flex justify-end">
                    <i class="fas fa-ellipsis fa-lg"></i>
                </div>
            </div>
        @endforeach

        <div class="flex fixed bottom-0 right-0 left-0 justify-center">
            <div>
                <button class="px-4 py-2 bg-blue-500 text-blue-100 m-2 rounded">Checkout</button>
            </div>
        </div>
    </section>

</body>

</html>