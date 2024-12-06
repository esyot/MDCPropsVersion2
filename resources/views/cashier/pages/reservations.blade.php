@extends('cashier.layouts.header')
@section('content')

<section>
    <nav class="flex justify-center p-2 border shadow-md">
        <div class="flex space-x-2">
            <form onsubmit="event.preventDefault();" hx-get="{{ route('cashier.reservation-search') }}"
                hx-target="#reservations" hx-swap="innerHTML" hx-trigger="input"
                class="flex items-center border space-x-1 bg-white rounded-full shadow-inner p-2">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Reservations" id="search_value" name="search_value"
                    class="bg-transparent focus:outline-none">
            </form>

            <button id="start-camera-btn" class="text-blue-500 hover:opacity-50 rounded">
                <i class="fas fa-camera fa-xl"></i>
            </button>

        </div>
    </nav>


    <div>
        <ul id="reservations" class="p-2">

        </ul>

    </div>

    <div id="reservation-details">

    </div>


    @if (session()->has('success'))


        <div id="successModal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">
            <div class="flex flex-col bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-circle-check fa-3x text-green-500"></i>
                    <h1 class="text-lg font-semibold ml-4">Payment Done Successfully!</h1>
                </div>
                <p class="text-gray-600">{{ session('success') }}</p>
                <button onclick="document.getElementById('successModal').classList.add('hidden')"
                    class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                    Close
                </button>
            </div>
        </div>
    @endif

</section>

<!-- QR Scan Modal -->
<div id="qr-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-4 rounded shadow-md">
        <!-- Webcam Display -->
        <div id="my_camera"></div>

        <div class="flex justify-end space-x-1">
            <button id="close-modal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Close</button>
            <button id="restart-camera" onclick="startCamera()"
                class="mt-4 bg-blue-500 text-blue-100 px-4 py-2 rounded">Open Camera</button>
        </div>
    </div>
</div>

<script src="{{ asset('asset/js/webcam.min.js') }}"></script>
<script>
    // Function to start camera and scanning
    function startCamera() {
        // Set up Webcam.js to start the camera
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#my_camera');
        continuousScan(); // Start continuous scanning after the camera feed starts
    }

    // Continuous scanning function
    function continuousScan() {
        // Continuously capture frames from the webcam and scan them
        Webcam.snap(function (data_uri) {
            // Create an image object from the captured frame
            const image = new Image();
            image.src = data_uri;

            image.onload = function () {
                // Create a canvas to draw the image for QR scanning
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = image.width;
                canvas.height = image.height;
                context.drawImage(image, 0, 0);

                // Get image data from the canvas
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                // Check if a QR code is detected
                if (code) {
                    console.log('QR Code detected:', code.data);
                    document.querySelector('input[name="search_value"]').value = code.data;
                    stopCamera(); // Stop the camera after QR code is detected

                    htmx.ajax('GET', '{{ route('cashier.reservation-search') }}?search_value='
                        + encodeURIComponent(code.data), { target: '#reservations' });

                } else {
                    // If no QR code detected, continue scanning
                    setTimeout(continuousScan, 100); // Retry scanning after a short delay
                }
            };
        });
    }

    // Stop the camera feed when a QR code is detected
    function stopCamera() {
        Webcam.reset(); // Stop the camera
    }

    // Event listener for the start scanning button
    document.getElementById('start-camera-btn').addEventListener('click', function () {
        startCamera(); // Start the camera when the button is clicked
        document.getElementById('qr-modal').classList.remove('hidden'); // Show the camera modal
        document.getElementById('start-camera-btn').style.display = 'none'; // Hide the start button after clicking
    });

    // Event listener for closing the modal
    document.getElementById('close-modal').addEventListener('click', function () {
        stopCamera(); // Stop the camera when modal is closed
        document.getElementById('qr-modal').classList.add('hidden'); // Hide the modal
        document.getElementById('start-camera-btn').style.display = 'inline-block'; // Show the start button again
    });
</script>

@endsection