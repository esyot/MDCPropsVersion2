<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/dist/qrious.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/main.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Cart</title>

</head>

<body class="bg-gray-200">

    @include('rentee.partials.errors.error-modal')

    <header class="flex items-center p-4 space-x-2 bg-blue-500 shadow-md">
        <a href="{{ route('rentee.back-to-home', ['rentee' => $rentee]) }}" class="hover:opacity-50">
            <i class="fas fa-arrow-circle-left fa-xl text-white"></i>
        </a>
        <h1 class="text-xl text-white font-bold">Cart</h1>
    </header>

    <section class="flex flex-col mt-2 overflow-y-auto">
        <form action="{{ route('checkout', ['rentee' => $rentee]) }}" method="GET" id="checkout-form"
            onsubmit="validateCheckout(event)" class="space-y-2">
            @csrf

            @if (count($properties) > 0)
                @foreach ($properties as $property)
                    <div class="flex justify-between p-4 mx-4 bg-white items-center">
                        <div class="flex space-x-2 items-center">
                            <input type="checkbox" name="properties[]" value="{{ $property->id }}"
                                id="property-{{ $property->id }}"
                                class="w-6 h-6 border-gray-300 rounded cursor-pointer focus:outline-none shadow-md" checked>

                            <img src="{{ asset('storage/images/categories/' . $property->category->folder_name . '/' . $property->img) }}"
                                alt="{{ $property->name }}"
                                class="w-[50px] h-[50px] object-cover border border-gray-300 shadow-md">
                            <p>{{$property->name}}</p>
                        </div>
                        <div>
                            <button type="button" class="hover:opacity-50" title="Remove this item in cart"
                                onclick="document.getElementById('remove-property-{{$property->id}}').classList.remove('hidden')">
                                <i class="fas fa-trash fa-lg text-red-500"></i>
                            </button>
                        </div>
                    </div>

                    <div id="remove-property-{{$property->id}}"
                        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
                        <div class="bg-white rounded shadow-md w-[500px] mx-2">
                            <div class="bg-red-500 py-1 rounded-t">

                            </div>
                            <div class="flex p-2 items-center space-x-2 border-b-2">
                                <div class="bg-red-500 px-3 py-2 rounded-full">
                                    <i class="fas fa-trash fa-lg text-white"></i>
                                </div>
                                <div class="flex flex-col justify-start items-start">
                                    <h1 class="text-xl font-medium text-center">Confirmation</h1>
                                    <div class="flex space-x-1">
                                        <p class="font-normal">Are you sure to remove </p>
                                        <p class="font-bold">{{$property->name}}</p>?
                                    </div>
                                </div>

                            </div>

                            <div class="flex justify-end p-2 items-center space-x-1">
                                <button type="button"
                                    onclick="document.getElementById('remove-property-{{$property->id}}').classList.add('hidden')"
                                    class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">
                                    No
                                </button>
                                <a href="{{route('rentee.cart-remove-property', ['id' => $property->id, 'rentee' => $rentee, 'properties' => '[]'])}}"
                                    class="px-4 p-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Yes</a>


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
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-blue-100 m-2 hover:opacity-50 rounded shadow-md">Proceed
                        to
                        checkout</button>
                </div>
            </div>
        </form>
    </section>


    <div id="empty-cart-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h2 class="text-xl font-semibold text-red-600 mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i> Error
            </h2>
            <p class="text-gray-700 mb-4">Please select at least one property to proceed to checkout.</p>
            <div class="flex justify-end">
                <button class="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600"
                    onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
    <script>
        function validateCheckout(event) {
            const checkboxes = document.querySelectorAll('input[name="properties[]"]:checked');
            if (checkboxes.length === 0) {
                event.preventDefault();
                document.getElementById('empty-cart-modal').classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('empty-cart-modal').classList.add('hidden');
        }
    </script>
</body>

</html>