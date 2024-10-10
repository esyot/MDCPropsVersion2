@extends('admin.layouts.header')
@section('content')
<div class="flex w-full bg-blue-300 p-2 shadow-md space-x-2">
    <div class="block p-2 bg-white rounded-full shadow-md">
        <i class="ml-2 fas fa-magnifying-glass"></i>
        <input id="search" type="text" placeholder="Search tracking no." class="bg-transparent focus:outline-none">
    </div>
    <button id="openScanner"
        class="px-2.5 py-2 hover:bg-gray-300 bg-white rounded transition-transform duration-300 ease-in-out hover:scale-110 shadow-md"
        title="Open QR Scanner">
        <i class="fas fa-qrcode fa-lg"></i>
    </button>
</div>
<section class="h-full">

</section>

<div id="scannerModal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40 hidden">
    <div class="bg-white p-2">
        <div id="preview">
            <video id="video" width="300" height="200" autoplay></video>
        </div>
        <div>
            <button id="startButton" class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">Start
                Scanning</button>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const startButton = document.getElementById('startButton');
    const scannerModal = document.getElementById('scannerModal');
    const openScannerButton = document.getElementById('openScanner');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');

    openScannerButton.addEventListener('click', async () => {
        scannerModal.classList.remove('hidden');
        await startCamera();
    });

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            requestAnimationFrame(scanQRCode);
        } catch (error) {
            console.error('Error accessing the camera:', error);
            alert('Could not access the camera. Please check permissions.');
        }
    }

    startButton.addEventListener('click', async () => {
        await startCamera();
    });

    function scanQRCode() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                console.log('QR Code detected:', code.data);
                document.getElementById('search').value = code.data;
            }
        }
        requestAnimationFrame(scanQRCode);
    }
</script>

@endsection