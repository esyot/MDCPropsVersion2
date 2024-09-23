<div id="transaction-add-{{$day}}"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div id="transaction-form-{{$day}}" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl relative">


        <!-- Modal Items  -->
        <div id="itemListModal-{{$day}}"
            class="flex fixed inset-0 bg-gray-800 justify-center items-center bg-opacity-50 z-50 hidden">
            <div class="bg-white px-4 rounded">
                <div class="flex items-center justify-between py-2">
                    <h1 class="text-xl font-medium">Select an Item</h1>
                    <button type="button" class="text-4xl"
                        onclick="document.getElementById('itemListModal-{{$day}}').classList.add('hidden')">&times;</button>

                </div>

                <div>



                    <div class="flex items-center space-x-1 p-2 border border-gray-300 rounded-full">
                        <form hx-get="{{ route('itemSearch', ['day' => $day]) }}" hx-trigger="input" hx-swap="innerHTML"
                            hx-target="#item-list-{{$day}}">
                            <i class="fas fa-search"></i>
                            <input type="text" class="focus:outline-none" name="input" placeholder="Search Item...">
                        </form>
                    </div>

                    <div class="border border-gray-200 my-2 bg-gray-100">
                        <div>
                            <ul id="item-list-{{$day}}">
                                @include('admin.pages.partials.inclusions.item')
                            </ul>
                        </div>
                    </div>


                </div>

            </div>
        </div>
        <form action="{{ route('transaction-create') }}" method="POST">
            @csrf <!-- CSRF token for security -->

            <!-- Close Button -->
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold"
                onclick="closeModalTransaction('{{$day}}')">
                &times;
            </button>

            <!-- Form Title -->
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Add a New Transaction in
                <span class="text-red-500 font-bold">{{ $currentCategory->title }}</span>
            </h2>

            <!-- Form Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Item Selection -->
                <div>
                    <label for="item_id" class="block text-gray-700 font-semibold mb-1">Item</label>
                    <input type="text" title="Items" id="item-{{$day}}"
                        onclick="document.getElementById('itemListModal-{{$day}}').classList.remove('hidden')"
                        class="block p-2 border border-gray-300 cursor-pointer rounded" placeholder="Select an item"
                        readonly required>
                </div>

                <!-- Hidden Item ID -->
                <input type="hidden" name="item_id" id="item-id-{{$day}}">
                <input type="hidden" name="category_id" value="{{ $currentCategory->id }}">

                <!-- Rentee Contact No -->
                <div>
                    <label for="rentee_contact_no" class="block text-gray-700 font-semibold mb-1">Rentee Contact
                        No</label>
                    <input id="rentee_contact_no" name="rentee_contact_no" type="text"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Rentee Contact No" required>
                </div>

                <!-- Rentee Name -->
                <div>
                    <label for="rentee_name" class="block text-gray-700 font-semibold mb-1">Rentee Name</label>
                    <input id="rentee_name" name="rentee_name" type="text"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Rentee Name" required>
                </div>

                <!-- Rentee Email -->
                <div>
                    <label for="rentee_email" class="block text-gray-700 font-semibold mb-1">Rentee Email</label>
                    <input id="rentee_email" name="rentee_email" type="email"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Rentee Email" required>
                </div>

                <!-- Rent Date -->
                <div>
                    <label for="rent_date" class="block text-gray-700 font-semibold mb-1">Rent Date</label>
                    <input id="rent_date" name="rent_date" type="date"
                        value="{{ $currentDate->copy()->day($day)->format('Y-m-d') }}"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Rent Time -->
                <div>
                    <label for="rent_time" class="block text-gray-700 font-semibold mb-1">Rent Time</label>
                    <input id="rent_time" name="rent_time" type="time"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Rent Return Date -->
                <div>
                    <label for="rent_return" class="block text-gray-700 font-semibold mb-1">Rent Return Date</label>
                    <input id="rent_return" name="rent_return" type="date"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Rent Return Time -->
                <div>
                    <label for="rent_return_time" class="block text-gray-700 font-semibold mb-1">Rent Return
                        Time</label>
                    <input id="rent_return_time" name="rent_return_time" type="time"
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Destination -->
                <div>
                    <label for="destination_id" class="block text-gray-700 font-semibold mb-1">Destination</label>
                    <select name="destination_id" id="destination_id"
                        class="block p-2 border border-gray-300 w-full custom-scrollbar rounded">
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->municipality }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="col-span-2 flex justify-end mt-4 space-x-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit
                </button>

                <button type="button" onclick="closeModalTransaction('{{$day}}')"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-800 text-gray-100 rounded">
                    Close
                </button>
            </div>
        </form>

    </div>
</div>

@if(session('success'))
    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
            <button onclick="document.getElementById('successModal').classList.add('hidden')"
                class="ml-4 bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Close
            </button>
        </div>
    </div>
@endif


@if($setting->transition == true)
    <script>
        function closeModalTransaction(day) {

            const modalId = 'transaction-add-' + day;
            const modalContentId = 'transaction-form-' + day;

            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalContentId);

            content.classList.remove('animation-open');
            content.classList.add('animation-close');


            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('animation-close');
            }, 150);
        }
    </script>
@else
    <script>
        function closeModalTransaction(day) {

            const modalId = 'transaction-add-' + day;
            const modalContentId = 'transaction-form-' + day;

            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalContentId);

            content.classList.remove('animation-open');
            content.classList.remove('animation-close');

            modal.classList.add('hidden');


        }
    </script>


@endif