<div class="flex flex-col">
    <label class="block text-gray-700 font-semibold mb-1" for="destination">Destination:</label>
    <div class="flex items-center space-x-2">
        <select class="flex-grow p-2 border border-gray-300 rounded" id="destinationList" name="destination">
            <option value="" class="" id="destination">
            </option>
            @foreach ($destinations as $destination)
                <option value="{{ $destination->municipality }}">{{ $destination->municipality }}</option>
            @endforeach
        </select>
        <button title="Open map" type="button" onclick="document.getElementById('map').classList.remove('hidden')"
            type="button" class="flex-shrink-0 px-4 py-2 bg-green-500 rounded text-green-100 hover:bg-green-800">
            <i class="fa-solid fa-map-location-dot"></i>

        </button>
    </div>
</div>

<!-- Section 3: Map -->
<div id="map" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white px-4 pb-4 mx-2 rounded-lg">
        <div class="flex items-center py-2 justify-between">
            <h1 class="text-2xl font-medium">Choose a destination</h1>
            <button type="button" onclick="document.getElementById('map').classList.add('hidden')"
                class="text-4xl">&times;</button>
        </div>

        <div class="flex map">

            @include('svg')
        </div>

        <div class="flex justify-end mt-2 space-x-1">

            <button type="button" onclick="cancelDestination()"
                class="px-4 py-2 border border-gray-300 hover:opacity-50 rounded">Cancel</button>
            <button type="button" onclick="document.getElementById('map').classList.add('hidden')"
                class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Save</button>
        </div>

        <script>
            function cancelDestination() {
                document.getElementById('map').classList.add('hidden');
                document.getElementById('destination').innerHTML = "<option value='tubigon' class='hidden' id='destination'></option>";
            }
        </script>

    </div>
</div>





<script>

    function handlePolygonClick(event) {
        const regionName = event.target.getAttribute('data-name');

        document.querySelectorAll('.municipality').forEach(polygon => {
            polygon.classList.remove('active');
        });


        event.target.classList.add('active');


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

    document.querySelectorAll('.municipality').forEach(polygon => {
        polygon.addEventListener('click', handlePolygonClick);
    });

    function closeModalTransaction(day) {
        document.getElementById('modalTransaction').style.display = 'none';
    }
</script>
</body>

</html>