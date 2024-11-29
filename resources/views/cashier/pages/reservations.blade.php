@extends('cashier.layouts.header')
@section('content')

<section>
    <nav class="flex items-center justify-between p-2 bg-blue-200">
        <h1 class="text-xl font-medium">Reservations</h1>
        <div class="flex space-x-2">


            <form onsubmit="event.preventDefault();" hx-get="{{ route('cashier.reservation-search') }}"
                hx-target="#reservations" hx-swap="innerHTML" hx-trigger="input"
                class="flex items-center space-x-1 bg-white rounded-full shadow-inner p-2">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Reservations" id="search_value" name="search_value"
                    class="bg-transparent focus:outline-none">
            </form>

            <button id="scan-qr-button" class="px-3 py-2 bg-blue-500 text-white hover:opacity-50 rounded">
                <i class="fas fa-camera fa-lg"></i>
            </button>

        </div>
    </nav>


    <div>
        <ul id="reservations" class="p-2">

        </ul>

    </div>

    <div id="reservation-details">

    </div>

    <div id="qr-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded shadow-md">
            <video id="video" width="300" height="300" autoplay></video>
            <div class="flex justify-end space-x-1">
                <button id="close-modal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                    Close
                </button>
                <button id="open-camera-button" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Open
                    Camera
                </button>

            </div>
        </div>
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

<script>
    const scanButton = document.getElementById('scan-qr-button');
    const qrModal = document.getElementById('qr-modal');
    const closeModalButton = document.getElementById('close-modal');
    const openCameraButton = document.getElementById('open-camera-button');
    const video = document.getElementById('video');
    let scanning = false;
    let cameraTimeout;

    scanButton.onclick = async () => {
        qrModal.classList.remove('hidden');
        await startCamera();
    };

    closeModalButton.onclick = () => {
        stopCamera();
        qrModal.classList.add('hidden');
    };

    openCameraButton.onclick = () => {
        reopenCamera();
    };

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            scanning = true;
            requestAnimationFrame(scanQRCode);
            cameraTimeout = setTimeout(() => {
                stopCamera();
                qrModal.classList.add('hidden');
            }, 60000);
        } catch (error) {
            console.error('Error accessing the camera:', error);
            alert('Could not access the camera. Please check permissions.');
        }
    }

    function stopCamera() {
        scanning = false;
        clearTimeout(cameraTimeout);
        const stream = video.srcObject;
        if (stream) {
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }
    }

    function reopenCamera() {
        qrModal.classList.remove('hidden');
        startCamera();
    }

    function scanQRCode() {
        if (!scanning) return;

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        if (video.videoWidth > 0 && video.videoHeight > 0) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                console.log('QR Code detected:', code.data);
                document.querySelector('input[name="search_value"]').value = code.data;
                stopCamera();
                qrModal.classList.add('hidden');

                htmx.ajax('GET', '{{ route('cashier.reservation-search') }}?search_value=' + encodeURIComponent(code.data), { target: '#reservations' });
            } else {
                requestAnimationFrame(scanQRCode);
            }
        } else {
            requestAnimationFrame(scanQRCode);
        }
    }

</script>

@endsection