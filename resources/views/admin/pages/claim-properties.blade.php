@extends('admin.layouts.header')
@section('content')
<style>
    @media(orientation:portrait) {

        #claim-items-header-content {
            display: flex;
            justify-content: center;
        }

        #claim-items-header-content form {
            width: 100%;
        }
    }
</style>
@include('admin.partials.success.success-modal')
@include('admin.partials.errors.error-modal')
<nav id="reservation-to-claim-header" class="p-2 w-full shadow-md">

    <div id="claim-items-header-content" class="flex space-x-2 justify-end">
        <form hx-get="{{ route('admin.search-reservation-to-claim') }}" hx-target="#reservation-to-claim-list"
            hx-swap="innerHTML" hx-trigger="input" class="p-2 focus:outline bg-white shadow-inner rounded-full"
            onsubmit="event.preventDefault();">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="search_value" class="bg-transparent focus:outline-none"
                placeholder="Input Tracking Code">
        </form>

        <button type="button" id="start-camera-btn"
            class="px-3 py-2 bg-blue-500 rounded-xl text-blue-100 hover:opacity-50 shadow-md rounded">
            <i class="fas fa-camera fa-lg"></i>
        </button>
    </div>

</nav>

<!-- QR Scan Modal -->
<div id="qr-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-4 rounded shadow-md">
        <!-- Webcam Display -->
        <div id="my_camera"></div>

        <div class="flex justify-end space-x-1">
            <button id="close-modal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Close</button>
            <button id="restart-camera" onclick="startCamera()"
                class="mt-4 bg-blue-500 text-blue-100 px-4 py-2 rounded">Start Scanning</button>
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

                    htmx.ajax('GET', '{{ route('admin.search-reservation-to-claim') }}?search_value='
                        + encodeURIComponent(code.data), { target: '#reservation-to-claim-list' });

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

<section class="p-2 w-full flex">
    <div id="reservation-to-claim-list" class="w-full overflow-y-auto">
    </div>
</section>
@endsection