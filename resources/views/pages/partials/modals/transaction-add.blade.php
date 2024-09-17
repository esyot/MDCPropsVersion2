<div id="transaction-add-{{$day}}"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div id="transaction-form-{{$day}}" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl relative">
        <form action="{{ route('transaction-create') }}" method="POST">
            @csrf <!-- CSRF token for security -->

            <!-- Close Button -->
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold"
                onclick="closeModalTransaction('{{$day}}')">
                &times;
            </button>

            <!-- Form Title -->
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add New Transaction</h2>

            <!-- Form Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Item Selection -->
                <div>
                    <label for="item_id" class="block text-gray-700 font-semibold mb-1">Item</label>
                    <select name="item_id" id="item_id" class="block py-2 px-2 border border-gray-300 rounded" required>
                        <option value="">Select an Item</option> <!-- Placeholder option -->
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden Category ID -->
                @foreach($currentCategory as $category)                  
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                @endforeach

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
                <div>
                    <label for="destination">Destination</label>
                    <select name="" id="" class="block p-2 border border-gray-300 w-full custom-scrollbar rounded">
                        <option value="antequera">Antequera</option>
                        <option value="baclayon">Baclayon</option>
                        <option value="balilihan">Balilihan</option>
                        <option value="batuan">Batuan</option>
                        <option value="bilar">Bilar</option>
                        <option value="buenavista">Buenavista</option>
                        <option value="calape">Calape</option>
                        <option value="candijay">Candijay</option>
                        <option value="carmen">Carmen</option>
                        <option value="catigbian">Catigbian</option>
                        <option value="clarin">Clarin</option>
                        <option value="danao">Danao</option>
                        <option value="dauis">Dauis</option>
                        <option value="dimiao">Dimiao</option>
                        <option value="duero">Duero</option>
                        <option value="garcia-hernandez">Garcia Hernandez</option>
                        <option value="jagna">Jagna</option>
                        <option value="lila">Lila</option>
                        <option value="loboc">Loboc</option>
                        <option value="loon">Loon</option>
                        <option value="mabini">Mabini</option>
                        <option value="maribojoc">Maribojoc</option>
                        <option value="panglao">Panglao</option>
                        <option value="pilar">Pilar</option>
                        <option value="president-carlos-p-garcia">President Carlos P. Garcia</option>
                        <option value="sagbayan">Sagbayan</option>
                        <option value="san-isidro">San Isidro</option>
                        <option value="san-miguel">San Miguel</option>
                        <option value="san-pascual">San Pascual</option>
                        <option value="sevilla">Sevilla</option>
                        <option value="sierra-bullones">Sierra Bullones</option>
                        <option value="talibon">Talibon</option>
                        <option value="trinidad">Trinidad</option>
                        <option value="tubigon">Tubigon</option>
                        <option value="ubay">Ubay</option>
                        <option value="valencia">Valencia</option>
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