<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">

    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>

    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>MDC Property Rental & Reservation Management System</title>
</head>

<body class="bg-gray-200 overflow-hidden">

    <header>
        <div class="flex items-center space-x-2 p-4 bg-gradient-to-r from-blue-500 to-blue-800">
            <a href="{{ route('welcome') }}" class="hover:opacity-50 text-white">
                <i class="fas fa-arrow-circle-left fa-2xl"></i>
            </a>
            <h1 class="text-xl text-white">Tracking</h1>
        </div>
    </header>

    <section class="mt-4 flex justify-center space-x-2">
        <form id="searchbar" class="flex items-center space-x-1 bg-white px-4 py-2 rounded-full shadow-md w-96">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="search_val" class="bg-transparent focus:outline-none w-full"
                placeholder="Input tracking no.">
            <button type="submit" class="fas fa-arrow-circle-up fa-lg text-gray-500 hover:text-gray-800"></button>
        </form>
        <button id="scan-qr-button" class="px-3 py-2 bg-blue-500 text-blue-100 rounded hover:opacity-50 shadow-md"
            title="Scan QR Code">
            <i class="fa-solid fa-camera fa-lg"></i>
        </button>
    </section>

    <section id="transaction" class="flex justify-center mt-4 p-2">

        @if(request('search_val') == null)
            <div class="flex bg-white p-2 space-x-2">
                <h1>Input tracking code or just scan QR code.</h1>
            </div>
        @elseif(request('search_val') != null)
            @foreach ($transactions as $transaction)

                <div class="flex flex-col justify-start bg-white p-2 space-y-2 w-full">
                    <div class="flex space-x-2">
                        <p>Tracking code: </p>
                        <span>
                            {{ $transaction->tracking_code }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <p>Rentee: </p>
                        <span>
                            {{ $transaction->rentee->first_name }}
                            {{ $transaction->rentee->last_name }}
                        </span>
                    </div>

                    <div class="flex space-x-2">
                        <p>Status: </p>
                        @if ($transaction->approved_at != null && $transaction->status == 'approved')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Approved</span>
                                <i class="fas fa-check-circle text-green-500"></i>

                            </div>
                        @elseif ($transaction->approved_at == null && $transaction->status == 'pending')
                            <div class="flex items-center space-x-2">
                                <span class="text-orange-500">Pending</span>
                                <i class="fa-solid fa-hourglass-start text-orange-500"></i>

                            </div>
                        @elseif ($transaction->status == 'canceled')
                            <div class="flex items-center space-x-2">
                                <span class="text-red-500">Canceled</span>
                                <i class="fa-solid fa-ban text-red-500"></i>

                            </div>
                        @elseif ($transaction->status == 'declined')
                            <div class="flex items-center space-x-2">
                                <span class="text-red-500">Declined</span>
                                <i class="fa-solid fa-ban text-red-500"></i>

                            </div>
                        @elseif ($transaction->status == 'in progress')
                            <div class="flex items-center space-x-2">
                                <span class="text-blue-500">In Progress</span>
                                <i class="fa-solid fa-business-time text-blue-500"></i>

                            </div>

                        @elseif ($transaction->status == 'occupied')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Occupied</span>
                                <i class="fa-solid fa-check-circle text-green-500"></i>

                            </div>

                            @elseif ($transaction->status == 'completed')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Completed</span>
                                <i class="fa-solid fa-check-circle text-green-500"></i>

                            </div>

                        @endif
                    </div>
                    @php
                        $formattedTransactionDate = \Carbon\Carbon::parse($transaction->created_at)->format('l, F j, Y h:i A');
                    @endphp
                    <div class="flex space-x-2">
                        <p>Transaction Date: </p> <span>{{ $formattedTransactionDate }}</span>
                    </div>

                    <div>
                        <h1>Requested Items:</h1>
                    </div>


                    @foreach ($items as $item)
                        @php
                            $formattedItemRentDate = \Carbon\Carbon::parse($item->rent_date)->format('F j, Y');
                            $formattedItemRentReturnDate = \Carbon\Carbon::parse($item->rent_return)->format('F j, Y');

                        @endphp
                        @php
                            $formattedRentTime = \Carbon\Carbon::parse($transaction->rent_time)->format('h:i A');
                            $formattedRentReturnTime = \Carbon\Carbon::parse($transaction->rent_return_time)->format('h:i A');
                        @endphp
                        <div class="flex flex-col border border-gray-300 p-2">
                            <p>Request {{$item->qty}} pc/s of <strong>{{ $item->item->name }}</strong>
                                for this {{$formattedItemRentDate}} {{$formattedRentTime}} to {{$formattedItemRentReturnDate}}
                                {{$formattedRentReturnTime}}.
                            </p>

                            <div class="flex space-x-2">



                                @if ($item->approvedByAdmin_at != null && $item->approvedByCashier_at != null && $item->canceledByRentee_at == null)
                                    <span>

                                        <span class="text-green-500">Approved</span>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </span>
                                @elseif ($item->approvedByAdmin_at != null && $item->approvedByCashier_at == null && $item->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-blue-500">Waiting for payment</span>
                                            <i class="fa-solid fa-credit-card text-blue-500"></i>
                                        </div>



                                    </div>
                                @elseif ($item->canceledByRentee_at != null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-red-500">Canceled</span>
                                            <i class="fa-solid fa-ban text-red-500"></i>
                                        </div>



                                    </div>
                                @elseif ($item->approvedByAdmin_at == null && $item->declinedByAdmin_at == null && $item->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-orange-500">Pending Admin Approval</span>
                                            <i class="fas fa-hourglass-start text-orange-500"></i>
                                        </div>



                                    </div>
                                @elseif ($item->declinedByAdmin_at != null && $item->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-red-500">Declined Request</span>
                                            <i class="fa-solid fa-ban text-red-500"></i>
                                        </div>
                                        <div class="flex space-x-1">
                                            <h1>Message:</h1>
                                            <span>{{$item->message}}</span>
                                        </div>


                                    </div>



                                @endif

                                @if($item->claimed_at == null && $item->approvedByCashier_at != null)

                                    <div class="flex items-center space-x-1 items-center">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-orange-500">Waiting to claim</span>
                                            <i class="fas fa-hourglass-start text-orange-500"></i>
                                        </div>
                                    </div>
                                @elseif($item->claimed_at != null && $item->approvedByCashier_at != null)

                                    <div class="flex items-center space-x-1 items-center">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-green-500">Claimed</span>
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        </div>
                                    </div>
                                @endif

                                @if($item->returned_at != null)

<div class="flex items-center space-x-1 items-center">
    <div class="flex items-center space-x-1">
        <span class="text-green-500">Returned   </span>
        <i class="fas fa-check-circle text-green-500"></i>
    </div>
</div>
@endif

                                @php
    $dateToday = now()->format('m:d:Y');

    if ($item->rent_return) {
    $rentReturnDate = \Carbon\Carbon::parse($item->rent_return)->format('m:d:Y');
} else {
    $rentReturnDate = null;
}
@endphp


@if($rentReturnDate === $dateToday && $item->approvedByCashier_at != null && $item->claimed_at != null)
    <div class="flex items-center space-x-1">
        <span class="text-orange-500">Waiting for return</span>
        <i class="fas fa-hourglass-start text-orange-500"></i>
    </div>
@endif


                            </div>
                        </div>
                    @endforeach
                    @if($transaction->status != 'completed' && $transaction->status != 'approved' && $transaction->status != 'canceled' && $transaction->status != 'occupied' && $transaction->status != 'declined')
                        <div onclick="document.getElementById('reservation-cancel-confirm-{{$transaction->id}}').classList.remove('hidden')"
                            class="flex justify-end">
                            <button class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Cancel Reservation</button>
                        </div>
                    @endif
                </div>

                <!-- Cancel reservation confirmation modal  -->

                <div id="reservation-cancel-confirm-{{$transaction->id}}"
                    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden">
                    <div class="bg-white rounded shadow-md">
                        <div class="bg-red-500 py-1 rounded-t">

                        </div>
                        <div class="flex p-2 border-b-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-exclamation-circle fa-2xl text-red-500"></i>
                                <div class="flex flex-col">
                                    <h1 class="text-xl font-medium">Cancelation</h1>
                                    <span>Are you sure to cancel your reservation?</span>
                                    <small>Note: This action cannot be undone.</small>
                                </div>

                            </div>
                        </div>
                        <div class="flex justify-end p-2 space-x-1 bg-gray-100 rounded-b">
                            <button
                                onclick="document.getElementById('reservation-cancel-confirm-{{$transaction->id}}').classList.add('hidden')"
                                class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">No</button>

                            <a href="{{ route('rentee.reservation-cancel', ['tracking_code' => $transaction->tracking_code]) }}"
                                class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Yes</a>
                        </div>
                    </div>
                </div>

            @endforeach

            @if(count($transactions) == 0)
                <div class="flex bg-white p-2 space-x-2">
                    <span>No results found.</span>
                </div>
            @endif
        @endif
    </section>

    <footer class="flex fixed bottom-0 left-0 right-0 justify-center">
        <div class="p-2">
            <p>All rights reserved &copy; 2024</p>
        </div>
    </footer>

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

                // Set a timeout to stop the camera after 1 minute
                cameraTimeout = setTimeout(() => {
                    stopCamera();
                    qrModal.classList.add('hidden');
                }, 60000); // 60000 milliseconds = 1 minute
            } catch (error) {
                console.error('Error accessing the camera:', error);
                alert('Could not access the camera. Please check permissions.');
            }
        }

        function stopCamera() {
            scanning = false;
            clearTimeout(cameraTimeout); // Clear the timeout if the camera is stopped
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
                    document.querySelector('input[name="search_val"]').value = code.data;
                    stopCamera();
                    qrModal.classList.add('hidden');

                    document.getElementById('searchbar').submit();
                } else {
                    requestAnimationFrame(scanQRCode);
                }
            } else {
                requestAnimationFrame(scanQRCode);
            }
        }
    </script>
</body>

</html>