<style>
    @media(orientation: portrait) {
        #reservation-form-fields-{{$day}} {
            height: 380px;
            overflow-y: auto;
            margin-left: 10px;
            margin-right: 10px;
        }
    }

    @media(orientation: landscape) {
        #reservation-form-fields-{{$day}} {
            width: 600px;
            margin-left: 10px;
            margin-right: 10px;
        }
    }
</style>

<script>
    function closePropertyListModal(day) {

        const count = document.getElementById('field-no-' + day).value;

        const newVal = document.getElementById('new-selected-property-id-' + day).value;
        const allVal = document.getElementById('all-selected-properties-on-' + day).value;

        const textArray = allVal.split('');

        textArray[count - 1] = newVal;

        const newText = textArray.join('');

        document.getElementById('all-selected-properties-on-' + day).value = newText;

        document.getElementById('propertiesListModal-' + day).classList.toggle('hidden');
    }
</script>


<!-- Modal Items  -->
<div id="propertiesListModal-{{$day}}"
    class="flex fixed inset-0 bg-gray-800  justify-center items-center bg-opacity-50 z-50 hidden">
    <div class="bg-white px-4 rounded shadow-md">

        <!-- hidden fields -->
        <input id="field-no-{{$day}}" type="hidden">
        <input type="hidden" id="new-selected-property-id-{{$day}}">

        <div class="flex items-center justify-between py-2">
            <h1 class="text-xl font-medium">Select a property</h1>
            <button type="button" class="text-4xl hover:opacity-50" onclick="closePropertyListModal({{$day}})">
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
            <h2 class="text-xl font-bold  text-gray-800">Add Reservation on
                <span class="text-red-500 font-bold">{{ $currentDate->copy()->day($day)->format('F j, Y ') }}</span>
            </h2>

            <button type="button" class="flex justify-end text-gray-500 hover:text-gray-700 text-2xl font-bold"
                onclick="closeModalReservation('{{$day}}')">
                &times;
            </button>
        </div>

        <div id="properties-{{$day}}" class="overflow-y-auto max-h-[150px] custom-scrollbar space-y-2 p-2">
            <div id="property-selected-on-{{$day}}-1" class="flex items-center space-x-4">
                <div class="flex-1">
                    <label for="property-name-{{$day}}-1"
                        class="block text-gray-700 font-semibold mb-1">Property:</label>
                    <div id="property-container-1" onclick="
                document.getElementById('propertiesListModal-{{$day}}').classList.remove('hidden')
                document.getElementById('field-no-{{$day}}').value = 1;"
                        class="flex items-center justify-between w-full p-2 border border-gray-300 cursor-pointer rounded">
                        <input type="text" title="Items" id="property-name-{{$day}}-1"
                            class="focus:outline-none cursor-pointer w-full" placeholder="Select a property" readonly
                            required>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>

                <div class="flex-1">
                    <label for="property-qty-{{$day}}-1"
                        class="block text-gray-700 font-semibold mb-1">Quantity:</label>
                    <input type="number" placeholder="0" name="property-qty-1" id="property-qty-{{$day}}-1"
                        class="block p-2 border border-gray-300 rounded w-full">
                </div>

                <script>
                    document.getElementById('property-qty-{{$day}}-1').addEventListener('input', function () {
                        let value = parseInt(document.getElementById('property-qty-{{$day}}-1').value, 10);

                        if (value < document.getElementById('property-qty-{{$day}}-1').min) {
                            document.getElementById('property-qty-{{$day}}-1').value = document.getElementById('property-qty-{{$day}}-1').min;
                        }

                        if (value > document.getElementById('property-qty-{{$day}}-1').max) {
                            document.getElementById('property-qty-{{$day}}-1').value = document.getElementById('property-qty-{{$day}}-1').max;
                        }
                    });

                </script>

                <input type="hidden" id="property-id-{{$day}}-1">

            </div>

        </div>
        <div class="flex justify-end px-2">
            <button type="button"
                class="px-2 py-2 space-x-1 bg-blue-500 text-blue-100 hover:opacity-50 flex items-center border border-gray-300 rounded"
                onclick="insertProperty({{$day}})">
                <i class="fas fa-plus fa-lg"></i>
                <h1>Insert property</h1>

            </button>
        </div>
        <div>

            <input type="hidden" name="propertiesId" id="all-selected-properties-on-{{$day}}">
            <input type="hidden" name="category_id" value="{{$currentCategory->id}}">
        </div>

        <!-- Form Inputs -->
        <div id="reservation-form-fields-{{$day}}" class="grid grid-cols-2 gap-2">


            <!-- Rentee Contact No -->
            <div>
                <label for="contact_no" class="block text-gray-700 font-semibold">Contact
                    No:</label>
                <input id="contact_no" name="contact_no" type="number"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Rentee Contact No" required>
            </div>

            <!-- Rentee First Name -->
            <div>
                <label for="name" class="block text-gray-700 font-semibold">Full Name:</label>
                <input id="name" name="name" type="text"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Full name" required>
            </div>

            <!-- Rentee Address 1 -->
            <div>
                <label for="address" class="block text-gray-700 font-semibold">Address</label>
                <input id="address" name="address" type="text"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Address" required>
            </div>

            <!-- Rentee Email -->
            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email:</label>
                <input id="email" name="email" type="email"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Email" required>
            </div>

            <!-- Rent Date -->
            <div class="hidden">
                <label for="date_start" class="block text-gray-700 font-semibold">Date Start:</label>
                <input id="date_start" name="date_start" type="date"
                    value="{{ $currentDate->copy()->day($day)->format('Y-m-d') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Rent Time -->
            <div>
                <label for="time_start" class="block text-gray-700 font-semibold">Time Start:</label>
                <input id="time_start" name="time_start" type="time"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Rent Return Date -->
            <div>
                <label for="date_end" class="block text-gray-700 font-semibold">Date End:</label>
                <input id="date_end" name="date_end" type="date"
                    value="{{ $currentDate->copy()->day($day)->format('Y-m-d') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Rent Return Time -->
            <div>
                <label for="time_end" class="block text-gray-700 font-semibold">Time End:</label>
                <input id="time_end" name="time_end" type="time"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Destination -->
            <div>
                <label for="destination_id" class="block text-gray-700 font-semibold">Destination:</label>
                <select name=" destination_id" id="destination_id"
                    class="block p-2 border border-gray-300 w-full custom-scrollbar rounded">
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}">{{ $destination->municipality }}</option>
                    @endforeach
                </select>
            </div>


            <div>
                <label for="" class="block text-gray-700 font-semibold">Reservation Type:</label>
                <select name="reservation_type" id="" class="block p-2 border border-gray-300 rounded w-full ">
                    <option value="borrow">Borrow</option>
                    <option value="rent">Rent</option>
                </select>
            </div>
            <div>
                <label for="" class="block text-gray-700 font-semibold">Assigned Personel: </label>
                <input type="text" name="assigned_personel" class="block p-2 w-full border border-gray-300 rounded"
                    placeholder="Personel of the property.">
            </div>

        </div>

        <div class="px-4">
            <label for="" class="block text-gray-700 font-semibold">Purpose</label>
            <textarea name="purpose" id="" placeholder="Input the purpose of reservation..."
                class="block w-full border border-gray-300 rounded"></textarea>
        </div>

        <!-- Submit and Cancel Buttons -->
        <div class="col-span-2 flex justify-end p-2 space-x-1">


            <button type="button" onclick="closeModalReservation('{{$day}}')"
                class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">
                Close
            </button>

            <button id="submit-btn-{{$day}}" type="submit"
                class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
                Submit
            </button>
        </div>
    </form>
    <script>
        document.getElementById('reservation-form-{{$day}}').addEventListener('submit', function (event) {
            document.getElementById('submit-btn-{{$day}}').disabled = true;
            document.getElementById('submit-btn-{{$day}}').innerText = 'Submitting';
        });
    </script>

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