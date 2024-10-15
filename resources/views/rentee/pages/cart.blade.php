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
            Cart
        </h1>

    </header>

    <section class="flex flex-col mt-2 overflow-y-auto">
        @foreach ($items as $item)
            <div class="flex justify-between p-4 mx-4 bg-white items-center">
                <div class="flex space-x-2 items-center">
                    <input type="checkbox" name="" id="">
                    <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                        alt="{{ $item->name }}" class="w-[50px] h-[50px] object-cover">

                    <p>{{$item->name}}</p>
                </div>
                <div>
                    <i class="fas fa-trash"></i>
                </div>
            </div>
        @endforeach

        <div class="flex fixed bottom-0 right-0 left-0  justify-center">
            <div>


                <button class="px-4 py-2 bg-yellow-500 text-yellow-100 m-2 rounded">Proceed to checkout</button>
            </div>
        </div>
    </section>

</body>

</html>