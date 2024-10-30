@extends('cashier.layouts.header')
@section('content')

<section>
    <nav class="flex items-center justify-between p-2 bg-blue-200">
        <h1 class="text-xl font-medium">Reservations</h1>
        <div class="flex space-x-2">


            <form hx-get="{{ route('cashier.reservation-search') }}" hx-target="#reservations" hx-swap="innerHTML"
                hx-trigger="input" class="flex items-center space-x-1 bg-white rounded-full shadow-inner p-2">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Reservations" id="search_value" name="search_value"
                    class="bg-transparent focus:outline-none">
            </form>
            <button id="scan-qr-button" class="py-2 px-3 bg-blue-500 text-white hover:opacity-50 rounded">
                <i class="fas fa-qrcode fa-lg"></i>

            </button>
        </div>
    </nav>


    <div>
        <ul id="reservations" class="p-2">
            @include('cashier.partials.reservations')

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