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

    <section class="flex flex-col mt-2  overflow-y-auto">
        <form action="{{ route('checkout', ['rentee' => $rentee]) }}" method="POST" id="checkout-form"
            class="space-y-2">
            @csrf

            @if (count($items) > 0)


                @foreach ($items as $item)
                    <div class="flex justify-between p-4 mx-4 bg-white items-center">
                        <div class="flex space-x-2 items-center">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}" id="item-{{ $item->id }}">
                            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                alt="{{ $item->name }}" class="w-[50px] h-[50px] object-cover">

                            <p>{{$item->name}}</p>
                        </div>
                        <div>
                            <button type="button"
                                onclick="document.getElementById('remove-item-{{$item->id}}').classList.remove('hidden')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div id="remove-item-{{$item->id}}"
                        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden">
                        <div class="p-4 bg-white space-y-2 rounded shadow-md">

                            <h1 class="text-xl text-center">Are you sure to remove this <br> item from our cart?</h1>

                            <div class="flex flex-col items-center justify-center">
                                <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                    alt="{{ $item->name }}" class="w-[50px] h-[50px] object-cover">
                                <p>{{$item->name}}</p>
                            </div>

                            <div class="flex justify-center items-center space-x-1">
                                <button type="button"
                                    onclick="document.getElementById('remove-item-{{$item->id}}').classList.add('hidden')"
                                    class="px-4 py-2 border border-gray-300 hover:bg-gray-500 hover:text-gray-100 rounded">
                                    No
                                </button>

                                <a href="{{route('removeItemInCart', ['id' => $item->id, 'rentee' => $rentee])}}"
                                    class="px-4 p-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Yes</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex justify-center">
                    <h1>Cart is empty</h1>
                </div>

            @endif

            <div class="flex fixed bottom-0 right-0 left-0 justify-center">
                <div>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-yellow-100 m-2 rounded">Proceed to
                        checkout</button>
                </div>
            </div>
        </form>
    </section>

</body>

</html>