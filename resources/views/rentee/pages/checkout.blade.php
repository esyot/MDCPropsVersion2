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
<div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
        <h2 class="text-xl font-semibold text-red-600 mb-4">Error</h2>
        <p class="text-gray-700 mb-4">Please complete the inputs first before proceeding to checkout.</p>
        <div class="flex justify-end">
            <button class="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600"
                onclick="document.getElementById('error-modal').classList.add('hidden')">Close</button>
        </div>
    </div>
</div>


<body class="bg-gray-200">
    <header class="flex items-center p-4 space-x-2 bg-blue-500 shadow-md">
        <a href="{{ route('backToHome', ['rentee' => $rentee]) }}" class="hover:opacity-50">
            <i class="fas fa-arrow-circle-left fa-xl text-white"></i>
        </a>
        <h1 class="text-xl text-white font-bold">Checkout</h1>
    </header>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- <input type="text" value="{{ $rentee }}" name="rentee"> -->
    <form action="{{ route('renteeCreateTransaction', ['rentee' => $rentee]) }}" method="POST">
        @csrf

        <div id="rentee-details"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div>
                    <h1 class="text-xl font-bold mb-4 text-center col-span-2">Input Details</h1>
                </div>
                <div class="w-96 grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                        <input type="text" name="first_name" placeholder="Enter First Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                        <input type="text" name="last_name" placeholder="Enter Last Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name:</label>
                        <input type="text" name="middle_name" placeholder="Enter Middle Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" name="email" placeholder="Enter Email"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact #:</label>
                        <input type="text" name="contact_no" placeholder="Enter Contact #"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="address1" class="block text-sm font-medium text-gray-700">Address Line 1:</label>
                        <input type="text" name="address_1" placeholder="Enter Address Line 1"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="address2" class="block text-sm font-medium text-gray-700">Address Line 2:</label>
                        <input type="text" name="address_2" placeholder="Enter Address Line 2"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 rounded">Checkout</button>
                    </div>
                </div>
            </div>
        </div>

        <section id="item-details" class="mt-2 overflow-y-auto space-y-2 p-4">
            @foreach ($items as $item)
                <input type="hidden" name="items[{{ $item->id }}][item_id]" value="{{ $item->id }}">

                <div class="flex p-4 bg-white justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                            alt="{{ $item->name }}" class="w-[50px] h-[50px] object-cover mr-2">
                        <p>{{$item->name}}</p>
                    </div>

                    <div class="flex items-center">
                        <div>
                            <label>Destination:</label>
                            <select class="p-2 border border-gray-300 rounded" name="items[{{ $item->id }}][destination]">
                                @foreach ($destinations as $destination)

                                    <option value="{{$destination->id}}">{{$destination->municipality}}</option>

                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Quantity:</label>
                            <input type="number" max="{{ $item->qty }}" name="items[{{ $item->id }}][quantity]"
                                class="p-2 border border-gray-300 rounded" placeholder="Available items: {{ $item->qty }}"
                                required>
                        </div>
                        <script>
                            document.querySelector('input[name="items[{{ $item->id }}][quantity]"]').addEventListener('input', function () {
                                const max = parseInt(this.getAttribute('max'));
                                if (this.value > max) {
                                    this.value = max;
                                }
                            });
                        </script>

                        <div>
                            <label>Date Rent:</label>
                            <input type="date" name="items[{{ $item->id }}][rent_date]"
                                class="p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label>Time Rent:</label>
                            <input type="time" name="items[{{ $item->id }}][rent_time]"
                                class="p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label>Date Return:</label>
                            <input type="date" name="items[{{ $item->id }}][rent_return]"
                                class="p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label>Time Return:</label>
                            <input type="time" name="items[{{ $item->id }}][rent_return_time]"
                                class="p-2 border border-gray-300 rounded" required>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex fixed bottom-0 right-0 left-0 justify-center">
                <div>
                    <button type="button" onclick="validateInputs()"
                        class="px-4 py-2 bg-blue-500 text-blue-100 m-2 rounded">Next</button>
                </div>
            </div>
        </section>
    </form>

    <script>
        function validateInputs() {
            const itemInputs = document.querySelectorAll('#item-details input');
            for (const input of itemInputs) {
                if (input.value.trim() === '') {
                    document.getElementById('error-modal').classList.remove('hidden')
                    return false;
                }
            }
            document.getElementById('rentee-details').classList.remove('hidden');
            return true;
        }
    </script>
</body>

</html>