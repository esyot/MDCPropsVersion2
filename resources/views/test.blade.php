<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Custom Polygon Map</title>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/MDC-logo-clipped.png') }}" type="image/png">
</head>

<body>
    <div class="modal" id="modalTransaction">
        <div class="modal-content">
            <!-- Section 1: Form Inputs -->
            <div class="form-section">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add New Transaction</h2>
                <form action="#" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="item_id" class="block text-gray-700 font-semibold mb-1">Item</label>
                            <select name="item_id" id="item_id" class="block py-2 px-2 border border-gray-300 rounded"
                                required>
                                <option value="">Select an Item</option>
                                <!-- Add your options here -->
                            </select>
                        </div>

                        <div>
                            <label for="rentee_contact_no" class="block text-gray-700 font-semibold mb-1">Rentee Contact
                                No</label>
                            <input id="rentee_contact_no" name="rentee_contact_no" type="text"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Rentee Contact No" required>
                        </div>

                        <div>
                            <label for="rentee_name" class="block text-gray-700 font-semibold mb-1">Rentee Name</label>
                            <input id="rentee_name" name="rentee_name" type="text"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Rentee Name" required>
                        </div>

                        <div>
                            <label for="rentee_email" class="block text-gray-700 font-semibold mb-1">Rentee
                                Email</label>
                            <input id="rentee_email" name="rentee_email" type="email"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Rentee Email" required>
                        </div>

                        <div>
                            <label for="rent_date" class="block text-gray-700 font-semibold mb-1">Rent Date</label>
                            <input id="rent_date" name="rent_date" type="date" value=""
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="rent_time" class="block text-gray-700 font-semibold mb-1">Rent Time</label>
                            <input id="rent_time" name="rent_time" type="time"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label for="rent_return" class="block text-gray-700 font-semibold mb-1">Rent Return
                                Date</label>
                            <input id="rent_return" name="rent_return" type="date"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label for="rent_return_time" class="block text-gray-700 font-semibold mb-1">Rent Return
                                Time</label>
                            <input id="rent_return_time" name="rent_return_time" type="time"
                                class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>


                    </div>
                    <div class="flex flex-col mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="destination">Destination</label>
                        <div class="flex items-center space-x-2">
                            <select class="flex-grow p-2 border border-gray-300 rounded" id="destinationList" name="">
                                <option value="tubigon" class="hidden" id="destination"></option>
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
                            <button onclick="document.getElementById('map').classList.remove('hidden')" type="button"
                                class="flex-shrink-0 px-4 py-2 bg-green-500 rounded text-green-100 hover:bg-green-800">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>Open Map</span>
                            </button>
                        </div>
                    </div>


            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="col-span-2 flex justify-end mt-4 space-x-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit
                </button>

                <button type="button" onclick="" class="px-4 py-2 bg-gray-500 hover:bg-gray-800 text-gray-100 rounded">
                    Close
                </button>
            </div>
            </form>
        </div>

        <!-- Section 3: Map -->
        <div id="map" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white px-4 pb-4">
                <div class="flex items-center py-2 justify-between">
                    <h1 class="text-2xl font-medium">Choose a destination</h1>
                    <button onclick="document.getElementById('map').classList.add('hidden')"
                        class="text-4xl">&times;</button>
                </div>

                <div class="flex map">

                    @include('svg')
                </div>

                <div class="flex justify-end mt-2 space-x-2">
                    <button onclick="document.getElementById('map').classList.add('hidden')"
                        class="px-4 py-2 bg-green-100 hover:bg-green-500 text-green-800 rounded">Save</button>
                    <button onclick="cancelDestination()" class=" px-4 py-2 bg-gray-100 hover:bg-gray-500 text-gray-800
                            rounded">Cancel</button>
                </div>

                <script>
                    function cancelDestination() {
                        document.getElementById('map').classList.add('hidden');
                        document.getElementById('destination').innerHTML = "<option value='tubigon' class='hidden' id='destination'></option>";
                    }
                </script>

            </div>



        </div>

    </div>
    </div>

    <script>
        // Function to handle click events
        function handlePolygonClick(event) {
            const regionName = event.target.getAttribute('data-name');
            console.log("You clicked on: " + regionName);

            // Remove 'active' class from all polygons
            document.querySelectorAll('.municipality').forEach(polygon => {
                polygon.classList.remove('active');
            });

            // Add 'active' class to the clicked polygon
            event.target.classList.add('active');

            // Log additional details about the clicked polygon
            console.log("Polygon points: " + event.target.getAttribute('points'));
            console.log("Current fill color: " + window.getComputedStyle(event.target).fill);

            document.getElementById('destinationList').classList.add('readonly');

            document.getElementById('destinationList').innerHTML = `
    <option value="${regionName}" id="destination">${regionName}</option>
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
    <option value="sevilla">Sevilla</option>
    <option value="sierra-bullones">Sierra Bullones</option>
    <option value="talibon">Talibon</option>
    <option value="trinidad">Trinidad</option>
    <option value="tubigon">Tubigon</option>
    <option value="ubay">Ubay</option>
    <option value="valencia">Valencia</option>
`;

        }

        // Add event listeners to all polygons
        document.querySelectorAll('.municipality').forEach(polygon => {
            polygon.addEventListener('click', handlePolygonClick);
        });

        function closeModalTransaction(day) {
            document.getElementById('modalTransaction').style.display = 'none';
        }
    </script>
</body>

</html>