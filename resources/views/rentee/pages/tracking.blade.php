<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
   <!-- Stylesheets -->
   <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/photos/logo.png') }}" type="image/png">

    <!-- JavaScript Libraries -->
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>
    <script src="{{ asset('asset/js/webcam.min.js') }}"></script>
   
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>

<body class="bg-gray-200 overflow-hidden">

    <header>
        <div class="flex items-center space-x-2 p-4 bg-gradient-to-r from-blue-500 to-blue-800">
            <a href="{{ route('rentee.welcome') }}" class="hover:opacity-50 text-white">
                <i class="fas fa-arrow-circle-left fa-2xl"></i>
            </a>
            <h1 class="text-xl text-white">Tracking</h1>
        </div>
    </header>

    <section class="mt-4 flex justify-center space-x-2 mx-2">
        <form id="searchbar" class="flex items-center space-x-1 bg-white px-4 py-2 rounded-full shadow-md w-96">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="search_val" class="bg-transparent focus:outline-none w-full"
                placeholder="Input tracking no.">
            <button type="submit" class="fas fa-arrow-circle-up fa-lg text-gray-500 hover:text-gray-800"></button>
        </form>
        <button id="start-camera-btn" class="px-3 py-2 bg-blue-500 text-blue-100 rounded hover:opacity-50 shadow-md"
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
            @foreach ($reservations as $reservation)

           
        <script src="{{ asset('asset/dist/qrious.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var trackingCode = '{{ $reservation->tracking_code }}';
        var qr = new QRious({
            element: document.getElementById('canvas'),
            value: trackingCode,
            size: 100
        });

        var qrLarge = new QRious({
            element: document.getElementById('QR-large'),
            value: trackingCode,
            size: 300
        });
    });
</script>

<div id="preview-qr-large" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden" onclick="closeModal(event)">
    <div class="bg-white rounded shadow-md">
        <div class="p-4">
            <canvas id="QR-large"></canvas>
        </div>
        <div class="flex justify-center space-x-1">
            <p>Tracking code: </p>
            <span>{{ $reservation->tracking_code }}</span>
        </div>
    </div>
</div>

<script>
    
    function closeModal(event) {
       
        if (event.target === document.getElementById('preview-qr-large')) {
            document.getElementById('preview-qr-large').classList.toggle('hidden');
        }
    }
</script>

                <div class="flex flex-col justify-start bg-white p-2 space-y-2 w-full">
                    <div class="flex space-x-2 justify-between">
                        <div class="flex flex-col">
                            <div class="flex space-x-1">
                            <p>Tracking code: </p>
                                <span>
                                    {{ $reservation->tracking_code }}
                                </span>
                            </div>
                      
                        <div class="flex space-x-2">
                        <p>Name: </p>
                        <span>
                            {{ $reservation->rentee->name }}
                        </span>
                       </div>
                       
                    <div class="flex space-x-2">
                        <p>Status: </p>
                        @if ($reservation->approved_at != null && $reservation->status == 'approved')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Approved</span>
                                <i class="fas fa-check-circle text-green-500"></i>

                            </div>
                        @elseif ($reservation->approved_at == null && $reservation->status == 'pending')
                            <div class="flex items-center space-x-2">
                                <span class="text-orange-500">Pending</span>
                                <i class="fa-solid fa-hourglass-start text-orange-500"></i>

                            </div>
                        @elseif ($reservation->status == 'canceled')
                            <div class="flex items-center space-x-2">
                                <span class="text-red-500">Canceled</span>
                                <i class="fa-solid fa-ban text-red-500"></i>

                            </div>
                        @elseif ($reservation->status == 'declined')
                            <div class="flex items-center space-x-2">
                                <span class="text-red-500">Declined</span>
                                <i class="fa-solid fa-ban text-red-500"></i>

                            </div>
                        @elseif ($reservation->status == 'in progress')
                            <div class="flex items-center space-x-2">
                                <span class="text-blue-500">In Progress</span>
                                <i class="fa-solid fa-business-time text-blue-500"></i>

                            </div>

                        @elseif ($reservation->status == 'occupied')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Occupied</span>
                                <i class="fa-solid fa-check-circle text-green-500"></i>

                            </div>

                            @elseif ($reservation->status == 'completed')
                            <div class="flex items-center space-x-2">
                                <span class="text-green-500">Completed</span>
                                <i class="fa-solid fa-check-circle text-green-500"></i>

                            </div>

                        @endif
                    </div>
                    @php
                        $formattedTransactionDate = \Carbon\Carbon::parse($reservation->created_at)->format('l, F j, Y h:i A');
                    @endphp
                    <div class="flex space-x-2">
                        <p>Date Submitted: </p> <span>{{ $formattedTransactionDate }}</span>
                    </div>
                        </div>

                        <div onclick="document.getElementById('preview-qr-large').classList.toggle('hidden')" class="hover:opacity-50 cursor-pointer">
                            <canvas id="canvas"></canvas>
                        </div>
                        
                    </div>
            
                    @foreach ($properties as $property)
                        @php
                            $formattedDateStart = \Carbon\Carbon::parse($property->date_start)->format('F j, Y');
                            $formattedDateEnd = \Carbon\Carbon::parse($property->date_end)->format('F j, Y');

                        @endphp
                        @php
                            $formattedTimeStart= \Carbon\Carbon::parse($property->time_start)->format('h:i A');
                            $formattedTimeEnd = \Carbon\Carbon::parse($property->time_end)->format('h:i A');
                        @endphp
                        <div class="flex flex-col border border-gray-300 p-2">
                            <p>Request {{$property->qty}} pc/s of <strong>{{ $property->property->name }}</strong>
                                for this <strong>{{$formattedDateStart}} {{$formattedTimeStart}} </strong>to 
                                <strong>{{$formattedDateStart}}
                                    {{$formattedTimeEnd}}.</strong>
                            </p>
                            <div class="flex space-x-1">
                                <h1>Destination:</h1>
                                <span>{{$property->destination->municipality}}</span>
                            </div>
                            <div class="flex space-x-1">
                                <h1>Reservation Type:</h1>
                                <span>{{ucfirst($property->reservation->reservation_type)}}</span>
                            </div>
                            <div class="flex space-x-2">

                                @if ($property->approvedByAdmin_at != null && $property->approvedByCashier_at != null && $property->canceledByRentee_at == null)
                                    <span>

                                        <span class="text-green-500">Approved</span>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </span>
                                @elseif ($property->approvedByAdmin_at != null && $property->approvedByCashier_at == null && $property->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-blue-500">Waiting for payment</span>
                                            <i class="fa-solid fa-credit-card text-blue-500"></i>
                                        </div>



                                    </div>
                                @elseif ($property->canceledByRentee_at != null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-red-500">Canceled</span>
                                            <i class="fa-solid fa-ban text-red-500"></i>
                                        </div>



                                    </div>
                                @elseif ($property->approvedByAdmin_at == null && $property->declinedByAdmin_at == null && $property->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1 items-center">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-orange-500">Pending Admin Approval</span>
                                            <i class="fas fa-hourglass-start text-orange-500"></i>
                                        </div>



                                    </div>
                                @elseif ($property->declinedByAdmin_at != null && $property->canceledByRentee_at == null)
                                    <div class="flex items-center space-x-1">
                                        <h1>Status:</h1>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-red-500">Declined Request</span>
                                            <i class="fa-solid fa-ban text-red-500"></i>
                                        </div>
                                        <div class="flex space-x-1">
                                            <h1>Message:</h1>
                                            <span>{{$property->message}}</span>
                                        </div>


                                    </div>



                                @endif

                                @if($property->claimed_at == null && $property->approvedByCashier_at != null)

                                    <div class="flex items-center space-x-1 items-center">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-orange-500">Waiting to claim</span>
                                            <i class="fas fa-hourglass-start text-orange-500"></i>
                                        </div>
                                    </div>
                                @elseif($property->claimed_at != null && $property->approvedByCashier_at != null)

                                    <div class="flex items-center space-x-1 items-center">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-green-500">Claimed</span>
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        </div>
                                    </div>
                                @endif

                                @if($property->returned_at != null)

<div class="flex items-center space-x-1 items-center">
    <div class="flex items-center space-x-1">
        <span class="text-green-500">Returned   </span>
        <i class="fas fa-check-circle text-green-500"></i>
    </div>
</div>
@endif

                                @php
    $dateToday = now()->format('m:d:Y');

    if ($property->rent_return) {
    $rentReturnDate = \Carbon\Carbon::parse($property->rent_return)->format('m:d:Y');
} else {
    $rentReturnDate = null;
}
@endphp


@if($formattedDateEnd === $dateToday && $property->approvedByCashier_at != null && $property->claimed_at != null  && $property->return_at != null)
    <div class="flex items-center space-x-1">
        <span class="text-orange-500">Waiting for return</span>
        <i class="fas fa-hourglass-start text-orange-500"></i>
    </div>
@endif


                            </div>
                        </div>
                    @endforeach
                    @if($reservation->status != 'completed' && $reservation->status != 'approved' && $reservation->status != 'canceled' && $reservation->status != 'occupied' && $reservation->status != 'declined')
                        <div onclick="document.getElementById('reservation-cancel-confirm-{{$reservation->id}}').classList.remove('hidden')"
                            class="flex justify-end">
                            <button class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Cancel Reservation</button>
                        </div>
                    @endif
                </div>

                <!-- Cancel reservation confirmation modal  -->

                <div id="reservation-cancel-confirm-{{$reservation->id}}"
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
                                onclick="document.getElementById('reservation-cancel-confirm-{{$reservation->id}}').classList.add('hidden')"
                                class="px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">No</button>

                            <a href="{{ route('rentee.reservation-cancel', ['tracking_code' => $reservation->tracking_code]) }}"
                                class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Yes</a>
                        </div>
                    </div>
                </div>

            @endforeach

            @if(count($reservations) == 0)
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
 
   <!-- QR Scan Modal -->
   <div id="qr-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded shadow-md">
            <!-- Webcam Display -->
            <div id="my_camera"></div>

            <div class="flex justify-end space-x-1">
                <button id="close-modal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

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
                        document.querySelector('input[name="search_val"]').value = code.data;
                        stopCamera(); // Stop the camera after QR code is detected
                        document.getElementById('searchbar').submit(); // Submit the form with QR code value
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
        document.getElementById('start-camera-btn').addEventListener('click', function() {
            startCamera(); // Start the camera when the button is clicked
            document.getElementById('qr-modal').classList.remove('hidden'); // Show the camera modal
            document.getElementById('start-camera-btn').style.display = 'none'; // Hide the start button after clicking
        });

        // Event listener for closing the modal
        document.getElementById('close-modal').addEventListener('click', function() {
            stopCamera(); // Stop the camera when modal is closed
            document.getElementById('qr-modal').classList.add('hidden'); // Hide the modal
            document.getElementById('start-camera-btn').style.display = 'inline-block'; // Show the start button again
        });
    </script>
</body>

</html>