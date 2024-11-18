<style>
    @media(orientation: portrait) {
        #reservation-form-fields-{{$day}} {
            height: 500px;
            overflow-y: auto;
            margin-left: 16px;
            margin-right: 16px;
        }
    }
</style>


<!-- Modal Items  -->
<div id="propertiesListModal-{{$day}}"
    class="flex fixed inset-0 bg-gray-800  justify-center items-center bg-opacity-50 z-50 hidden">
    <div class="bg-white px-4 rounded">
        <div class="flex items-center justify-between py-2">
            <h1 class="text-xl font-medium">Select a property</h1>
            <button type="button" class="text-4xl"
                onclick="document.getElementById('propertiesListModal-{{$day}}').classList.toggle('hidden')">
                &times;
            </button>
        </div>

        <div>
            <div class="flex items-center space-x-1 p-2 border border-gray-300 rounded-full">
                <form
                    hx-get="{{ route('admin.property-search', ['day' => $day, 'category_id' => $currentCategory->id]) }}"
                    hx-trigger="input" hx-swap="innerHTML" hx-target="#property-list-{{$day}}">
                    <i class="fas fa-search"></i>
                    <input type="text" class="focus:outline-none" name="input" placeholder="Search a property...">
                </form>
            </div>

            <div class="border border-gray-200 my-2 bg-gray-100">
                <div>
                    <ul id="property-list-{{$day}}">
                        @include('admin.partials.property')
                    </ul>
                </div>
            </div>


        </div>

    </div>
</div>



<div id="reservation-add-{{$day}}"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 select-none flex items-center justify-center z-40 hidden">
    <form action="{{ route('admin.reservation-add') }}" method="POST" id="reservation-form-{{$day}}"
        class="bg-white rounded shadow-md mx-2">
        @csrf

        <div class="flex justify-between px-4 py-2">

            <!-- Form Title -->
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Add Reservation on
                <span class="text-red-500 font-bold">{{ $currentCategory->title }}</span>
            </h2>

            <button type="button" class="flex justify-end text-gray-500 hover:text-gray-700 text-2xl font-bold"
                onclick="closeModalReservation('{{$day}}')">
                &times;
            </button>
        </div>


        <!-- Form Inputs -->
        <div id="reservation-form-fields-{{$day}}" class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4">

            <!-- Item Selection -->

            <div>
                <label for="property_id" class="block text-gray-700 font-semibold mb-1">Item</label>
                <div onclick="document.getElementById('propertiesListModal-{{$day}}').classList.remove('hidden')"
                    class="flex items-center justify-between block p-2 border border-gray-300 cursor-pointer rounded">
                    <input type="text" title="Items" id="property-{{$day}}" class="focus:outline-none cursor-pointer"
                        placeholder="Select a property" readonly required><i class="fa-solid fa-chevron-down"></i>

                </div>

            </div>
            <div>
                <label for="qty" class="block text-gray-700 font-semibold mb-1">Quantity</label>
                <input type="number" id="qty" placeholder="Quantity" name="qty"
                    class="block p-2 border border-gray-300 rounded w-full">
            </div>

            <!-- Hidden Item ID -->
            <input type="hidden" name="property_id" id="property-id-{{$day}}">
            <input type="hidden" name="category_id" value="{{ $currentCategory->id }}">

            <!-- Rentee Contact No -->
            <div>
                <label for="contact_no" class="block text-gray-700 font-semibold mb-1">Contact
                    No:</label>
                <input id="contact_no" name="contact_no" type="number"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Rentee Contact No" required>
            </div>

            <!-- Rentee First Name -->
            <div>
                <label for="name" class="block text-gray-700 font-semibold mb-1">Full Name:</label>
                <input id="name" name="name" type="text"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Full name" required>
            </div>

            <!-- Rentee Address 1 -->
            <div>
                <label for="address" class="block text-gray-700 font-semibold mb-1">Address</label>
                <input id="address" name="address" type="text"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Address" required>
            </div>



            <!-- Rentee Email -->
            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-1">Email:</label>
                <input id="email" name="email" type="email"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Email" required>
            </div>

            <!-- Rent Date -->
            <div>
                <label for="date_start" class="block text-gray-700 font-semibold mb-1">Date Start:</label>
                <input id="date_start" name="date_start" type="date"
                    value="{{ $currentDate->copy()->day($day)->format('Y-m-d') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Rent Time -->
            <div>
                <label for="time_start" class="block text-gray-700 font-semibold mb-1">Time Start:</label>
                <input id="time_start" name="time_start" type="time"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Rent Return Date -->
            <div>
                <label for="date_end" class="block text-gray-700 font-semibold mb-1">Date End:</label>
                <input id="date_end" name="date_end" type="date"
                    value="{{ $currentDate->copy()->day($day)->format('Y-m-d') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Rent Return Time -->
            <div>
                <label for="time_end" class="block text-gray-700 font-semibold mb-1">Time End:</label>
                <input id="time_end" name="time_end" type="time"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Destination -->
            <div>
                <label for="destination_id" class="block text-gray-700 font-semibold mb-1">Destination:</label>
                <select name="destination_id" id="destination_id"
                    class="block p-2 border border-gray-300 w-full custom-scrollbar rounded">
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}">{{ $destination->municipality }}</option>
                    @endforeach
                </select>
            </div>


            <div>
                <label for="" class="block text-gray-700 font-semibold mb-1">Reservation Type:</label>
                <select name="reservation_type" id="" class="block p-2 border border-gray-300 rounded w-full ">
                    <option value="borrow">Borrow</option>
                    <option value="rent">Rent</option>
                </select>
            </div>
            <div>
                <label for="" class="block text-gray-700 font-semibold mb-1">Assigned Personel: </label>
                <input type="text" name="assigned_personel" class="block p-2 w-full border border-gray-300 rounded"
                    placeholder="Personel of the property.">
            </div>

        </div>

        <div class="px-4">
            <label for="" class="block text-gray-700 font-semibold mb-1">Purpose</label>
            <textarea name="purpose" id="" placeholder="Input the purpose of reservation..."
                class="w-full border border-gray-300 rounded"></textarea>
        </div>

        <!-- Submit and Cancel Buttons -->
        <div class="col-span-2 flex justify-end p-2 space-x-1">


            <button type="button" onclick="closeModalReservation('{{$day}}')"
                class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">
                Close
            </button>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
                Submit
            </button>
        </div>
    </form>


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
        function closeModalReservation(day) {

            const modalId = 'reservation-add-' + day;
            const modalContentId = 'reservation-form-' + day;

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
        function closeModalReservation(day) {

            const modalId = 'reservation-add-' + day;
            const modalContentId = 'reservation-form-' + day;

            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalContentId);

            content.classList.remove('animation-open');
            content.classList.remove('animation-close');

            modal.classList.add('hidden');


        }
    </script>


@endif