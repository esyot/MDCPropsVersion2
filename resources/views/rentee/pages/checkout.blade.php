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

<style>
    @media(orientation:landscape) {}
</style>

<body class="bg-gray-100 h-screen">

    <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h2 class="text-xl font-semibold text-red-600 mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i> Error
            </h2>
            <p class="text-gray-700 mb-4">Please complete the input fields first before proceeding to checkout.</p>
            <div class="flex justify-end">
                <button class="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600"
                    onclick="document.getElementById('error-modal').classList.add('hidden')">Close</button>
            </div>
        </div>
    </div>


    <header class="flex py-4 items-center space-x-1 px-2 bg-blue-500">
        <a href="{{ route('rentee.back-to-home', ['rentee' => $rentee]) }}" class="hover:opacity-50">
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


    <form action="{{ route('rentee.reservation-add', ['rentee' => $rentee]) }}" method="POST">
        @csrf


        <div id="rentee-details"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-[500px] mx-2">
                <div class="flex items-center justify-between bg-blue-500 rounded-t-lg px-2 text-white">
                    <h1 class="text-xl font-bold">Input Details</h1>
                    <button class="text-2xl font-bold hover:opacity-50"
                        onclick="document.getElementById('rentee-details').classList.add('hidden')">&times;</button>
                </div>


                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-100 border-b-2">
                    <div class="mb-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name:</label>
                        <input type="text" name="name" placeholder="Enter First Name"
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
                        <label for="address1" class="block text-sm font-medium text-gray-700">Address:</label>
                        <input type="text" name="address" placeholder="Enter Address"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>


                </div>
                <div class="p-2 border-b-2">
                    <div>
                        <h1 class="text-xl font-bold">Terms & Conditions</h1>
                    </div>
                    <div class="flex text-xs space-x-1">
                        <input id="terms-checkbox" type="checkbox" name="" id="" class="hidden" required>
                        <div class="">
                            <span id="terms-checkbox-label-1">
                                Read first the
                            </span>
                            <span id="terms-checkbox-label-2" class="hidden">
                                I have read & accept the website's
                            </span>

                            <button type="button"
                                onclick="document.getElementById('terms-and-conditions').classList.remove('hidden')"
                                class="text-blue-500 hover:opacity-50 underline">Terms &
                                Conditions.
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-2 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit
                        Reservation</button>
                </div>
            </div>
        </div>

        <div id="terms-and-conditions"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white w-[600px] rounded shadow-md mx-2">
                <div class="border-b-2 p-2">


                    <h1 class="text-xl font-bold">Terms & Conditions</h1>
                    <p class="text-justify">By using this website, you agree to these Terms and Conditions. All listed
                        properties are for rent only, with no ownership rights conveyed. Services are provided "as is"
                        without guarantees, and we reserve the right to modify or discontinue any part of the site at
                        any time. You are responsible for accurate information and ensuring rented items are returned on
                        time and in the same condition. Any damage or costs related to rented properties are the
                        renter’s responsibility, and you agree to indemnify the website owner and its affiliates from
                        any claims or losses arising from your use of the site.</p>
                    <h1 class="text-xl font-bold mt-2">Privacy Notice</h1>
                    <p>
                        We value your privacy and are committed to protecting your personal data in accordance with the
                        Data
                        Privacy Act of 2012 (Republic Act No. 10173). By using this website, you consent to the
                        collection,
                        use, and processing of your personal information as described in our Privacy Policy. We will
                        take
                        all reasonable steps to protect your personal data and ensure it is handled securely. For
                        further
                        details on how we collect, use, and safeguard your personal information, please review our
                        Privacy
                        Policy.

                        This agreement is governed by the laws of the Philippines, and any disputes will be resolved in
                        the
                        appropriate courts located within the Philippines.</p>
                </div>
                <div class="flex justify-end p-2 bg-gray-100 rounded-b-lg">
                    <button type="button" onclick="updateTermsState()"
                        class="px-4 py-2 border border-gray-300 hover:opacity-50 shadow-md rounded">Done</button>

                </div>
            </div>
        </div>

        <script>
            function updateTermsState() {

                document.getElementById('terms-and-conditions').classList.add('hidden');
                document.getElementById('terms-checkbox').classList.remove('hidden');
                document.getElementById('terms-checkbox').checked = true;
                document.getElementById('terms-checkbox-label-1').classList.add('hidden');
                document.getElementById('terms-checkbox-label-2').classList.remove('hidden');
            }
        </script>


        <div class="flex overflow-x-auto">


            @foreach ($properties as $property)
                <div class="p-2 m-2 border border-gray-300 bg-white rounded">


                    <input type="hidden" name="properties[{{ $property->id }}][property_id]" value="{{ $property->id }}">

                    <div class="">
                        <div class="flex items-center justify-between">
                            <img src="{{ asset('storage/images/categories/' . $property->category->folder_name . '/' . $property->img) }}"
                                alt="{{ $property->name }}"
                                class="w-16 h-16 object-cover rounded-md border border-gray-300 shadow-md">
                            <p class="text-gray-800 text-lg font-medium">{{ $property->name }}</p>
                        </div>

                        <div class="">
                            <div class="flex flex-col">
                                <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                                <input type="number" max="{{ $property->qty }}"
                                    name="properties[{{ $property->id }}][quantity]"
                                    class="p-2 border border-gray-300 rounded" placeholder="Available: {{ $property->qty }}"
                                    required>
                            </div>
                            <script>
                                document.querySelector('input[name="properties[{{ $property->id }}][quantity]"]').addEventListener('input', function () {
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
        <section id="property-details" class="p-4 bg-white m-4 shadow-md rounded-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    @include('map')
                </div>



                <div>
                    <label for="time_end" class="block text-sm font-medium text-gray-700">Time End:</label>
                    <input type="time" name="time_end"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700">Date Start:</label>
                    <input type="date" value="{{ today()->toDateString() }}" name="date_start"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>




                <div>
                    <label for="time_start" class="block text-sm font-medium text-gray-700">Time Start:</label>
                    <input type="time" name="time_start"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700">Date End:</label>
                    <input type="date" value="{{ today()->toDateString() }}" name="date_end"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label for="">Reservation Type: </label>
                    <select name="reservation_type" id="" class="block p-2 w-full border border-gray-300 rounded">
                        <option value="borrow">Borrow</option>
                        <option value="rent">Rent</option>
                    </select>
                </div>
                <div>
                    <label for="" class="block text-sm font-medium text-gray-700">Purpose:</label>
                    <textarea name="purpose" id="purpose" placeholder="Input purpose..."
                        class="w-full border border-gray-300 rounded"></textarea>

                </div>

            </div>
        </section>

        <div class="flex fixed right-0 left-0 m-2 bottom-0 items-center justify-center">
            <button type="button" onclick="validateInputs()"
                class="px-6 py-3 bg-blue-500 text-blue-100 shadow-md rounded hover:opacity-50">Next</button>
        </div>
    </form>

    <script>
        function validateInputs() {
            const itemInputs = document.querySelectorAll('#property-details input');
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