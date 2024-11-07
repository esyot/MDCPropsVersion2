<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Checkout Dashboard</title>
</head>

<body class="bg-gray-100">

    <!-- Error Modal -->
    <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h2 class="text-xl font-semibold text-red-600 mb-4">Error</h2>
            <p class="text-gray-700 mb-4">Please complete the inputs first before proceeding to checkout.</p>
            <div class="flex justify-end">
                <button class="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600"
                    onclick="document.getElementById('error-modal').classList.add('hidden')">Close</button>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <header class="flex py-4 items-center space-x-1 px-2 bg-blue-500">
        <a href="{{ route('backToHome', ['rentee' => $rentee]) }}" class="hover:opacity-50">
            <i class="fas fa-arrow-circle-left fa-xl text-white"></i>
        </a>
        <h1 class="text-xl text-white font-bold">Checkout</h1>
    </header>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Checkout Form -->
    <form action="{{ route('renteeCreateTransaction', ['rentee' => $rentee]) }}" method="POST">
        @csrf

        <!-- Rentee Details Modal -->
        <div id="rentee-details"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold">Input Details</h1>
                    <button class="text-2xl font-bold hover:opacity-50"
                        onclick="document.getElementById('rentee-details').classList.add('hidden')">&times;</button>
                </div>

                <!-- Rentee Details Form -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-2">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                        <input type="text" name="first_name" placeholder="Enter First Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                        <input type="text" name="last_name" placeholder="Enter Last Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name:</label>
                        <input type="text" name="middle_name" placeholder="Enter Middle Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" name="email" placeholder="Enter Email"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>

                    <div class="mb-2">
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact #:</label>
                        <input type="text" name="contact_no" placeholder="Enter Contact #"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="block text-sm font-medium text-gray-700">Address Line 1:</label>
                        <input type="text" name="address_1" placeholder="Enter Address Line 1"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="address2" class="block text-sm font-medium text-gray-700">Address Line 2:</label>
                        <input type="text" name="address_2" placeholder="Enter Address Line 2"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>

                </div>
                <div class="p-2 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Checkout</button>
                </div>
            </div>
        </div>

        <!-- Item Details Section -->
        <div class="flex overflow-x-auto">


            @foreach ($items as $item)
                <div class="p-2 m-2 border border-gray-300 bg-white rounded">


                    <input type="hidden" name="items[{{ $item->id }}][item_id]" value="{{ $item->id }}">

                    <div class="">
                        <div class="flex items-center justify-between">
                            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                alt="{{ $item->name }}"
                                class="w-16 h-16 object-cover rounded-md border border-gray-300 shadow-md">
                            <p class="text-gray-800 text-lg font-medium">{{ $item->name }}</p>
                        </div>

                        <div class="">
                            <div class="flex flex-col">
                                <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                                <input type="number" max="{{ $item->qty }}" name="items[{{ $item->id }}][quantity]"
                                    class="p-2 border border-gray-300 rounded" placeholder="Available: {{ $item->qty }}"
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <section id="item-details" class="p-2 bg-white m-2 shadow-md rounded">

            <!-- Destination & Date Inputs -->
            <div class="space-y-4">
                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700">Destination:</label>
                    <select class="p-2 w-full border border-gray-300 rounded" name="destination" required>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->municipality }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="rent_date" class="block text-sm font-medium text-gray-700">Date Rent:</label>
                    <input type="date" value="{{ today()->toDateString() }}" name="rent_date"
                        class="p-2 w-full border border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="rent_time" class="block text-sm font-medium text-gray-700">Time Item Pickup:</label>
                    <input type="time" name="rent_time" class="p-2 w-full border border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700">Date Return:</label>
                    <input type="date" value="{{ today()->toDateString() }}" name="rent_return"
                        class="p-2 w-full border border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="return_time" class="block text-sm font-medium text-gray-700">Time Item Return:</label>
                    <input type="time" name="rent_return_time" class="p-2 w-full border border-gray-300 rounded"
                        required>
                </div>
            </div>
        </section>

        <div class="flex justify-center mt-6">
            <button type="button" onclick="validateInputs()"
                class="px-6 py-3 bg-blue-500 text-blue-100 shadow-md rounded hover:opacity-50">Next</button>
        </div>
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