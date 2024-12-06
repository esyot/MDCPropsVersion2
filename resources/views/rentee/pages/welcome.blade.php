<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/dist/qrious.js') }}"></script>


    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/photos/logo.png') }}" type="image/png">

    <!-- JavaScript Libraries -->
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>

    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>

<body class="font-sans h-screen overflow-hidden flex flex-col bg-gradient-to-b from-blue-800 to-blue-100">
    <!-- Tutorial -->
    <div id="imageModal"
        class="select-none fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-40 hidden">
        <div class="bg-white rounded-lg shadow-lg w-[800px] mx-2 z-50">
            <div class="relative shadow-md">
                <div class="carousel-container relative">
                    <img src="{{ asset('asset/photos/tutorial/1.jpg') }}" alt="Image 1" class="carousel-img"
                        id="carouselImage">
                </div>
                <button
                    class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-gray-800 text-white px-3.5 py-2 opacity-40 hover:opacity-100 rounded-full"
                    id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            const images = Array.from({ length: 17 }, (_, index) => `{{ asset('asset/photos/tutorial/') }}/${index + 1}.jpg`);

            let currentIndex = 0;

            const modal = document.getElementById("imageModal");
            const openModalBtn = document.getElementById("redirect-btn");
            const closeModalBtn = document.getElementById("closeModalBtn");
            const carouselImage = document.getElementById("carouselImage");
            const nextBtn = document.getElementById("nextBtn");
            const prevBtn = document.getElementById("prevBtn");

            if (openModalBtn) {

                openModalBtn.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                    document.getElementById('character-waving').classList.add('hidden');
                });
            }


            if (closeModalBtn) {

                closeModalBtn.addEventListener("click", () => {
                    modal.classList.add("hidden");
                });
            }


            if (nextBtn) {

                nextBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex + 1) % images.length;
                    carouselImage.src = images[currentIndex];


                    if (currentIndex === 16) {
                        modal.classList.add("hidden");
                    }
                });
            }


            if (prevBtn) {

                prevBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    carouselImage.src = images[currentIndex];
                });
            }
        });
    </script>

    <!-- /Tutorial -->

    <script>
        function clearData() {
            localStorage.removeItem('formData');
        }
        clearData();
    </script>

    @if (request('reservation') != null)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var trackingCode = '{{ $reservation->tracking_code }}';
                new QRious({
                    element: document.getElementById('canvas'),
                    value: trackingCode,
                    size: 300
                });
            });
        </script>

        <!-- QR Modal -->
        <div id="QR" class="fixed inset-0 flex justify-center items-center z-50 bg-black bg-opacity-50">
            <div class="w-[500px] bg-white rounded-lg shadow-lg p-4 z-10">
                <h1 class="text-xl font-bold text-center">Reservation submitted successfully!</h1>
                <div class="flex justify-center mt-4">
                    <canvas id="canvas"></canvas>
                </div>
                <div class="text-center mt-3">
                    <p>Tracking Code: <span class="font-bold">{{ $reservation->tracking_code }}</span></p>
                </div>
                <div class="flex justify-center mt-6">
                    <p class="text-xs text-justify border border-red-500 p-3">
                        <strong>Note:</strong> Save this QR code for tracking and payment.<br> Or click
                        <a href="{{ route('tracking', ['search_val' => $reservation->tracking_code]) }}"
                            class="text-blue-500 hover:underline">here</a> to track your request.
                    </p>
                </div>

                <div class="flex justify-center mt-2">
                    <button onclick="document.getElementById('QR').classList.add('hidden')"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">
                        Done
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Fixed tracking button -->
    <div class="flex fixed top-0 right-0 p-4 z-20">
        <a href="{{ route('tracking') }}" title="Check reservation"
            class="bg-blue-500 px-2 py-1.5 shadow-xl rounded-full hover:opacity-50">
            <i class="fas fa-desktop fa-lg text-blue-100"></i>
        </a>
    </div>


    <div id="character-waving" class="flex fixed bottom-2 left-[-50px] p-4 z-30">

        <span>
            <div class="fixed relative left-32 text-center text-[12px]">
                <p class="bg-white rounded-r-full rounded-tl-full to py-2 px-4 break-words w-[250px] shadow-md">
                    <span id="typing-text"> Hi there! are you new here? you can run tutorials by
                        clicking</span>
                    <button id="redirect-btn" class="hidden text-blue-500 hover:underline">here.</button>
                </p>


                <i class="fas fa-circle text-[10px] fixed left-20 text-white shadow-md"> </i>
                <i class="fas fa-circle text-[6px] fixed left-16 mt-4 text-white shadow-md"> </i>

            </div>

            <img src="{{ asset('asset/gif/wave-92.gif') }}" class="w-36 h-36 drop-shadow-md" alt="">
        </span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const textElement = document.getElementById("typing-text");
            const text = textElement.innerHTML;
            textElement.innerHTML = '';

            let index = 0;

            function typeWriter() {
                if (index < text.length) {
                    textElement.innerHTML += text.charAt(index);
                    index++;
                    setTimeout(typeWriter, 30)
                    document.getElementById('redirect-btn').classList.add('hidden');
                } else {

                    document.getElementById('redirect-btn').classList.remove('hidden');
                }

            }


            setInterval(function () {
                index = 0;
                textElement.innerHTML = '';
                typeWriter();
            }, 10000);


            typeWriter();
        });
    </script>


    <!-- Welcome Section -->
    <div id="welcome" class="flex fixed inset-0 justify-center items-center">
        <div class="bg-white px-12 rounded shadow-md">


            <section class="text-center ">
                <div class="flex justify-center p-2">
                    <img src="{{ asset('asset/logo/logo.png') }}" alt="" class="drop-shadow mt-2 w-20 h-20 bg-blue-500 rounded-full cursor-pointer select-none
    transition-all duration-150 [box-shadow:0_3px_0_0_#1b6ff8,0_6px_0_0_#1b70f841]
    border-b border-blue-400">
                </div>
                <h1 class="text-xl mt-2 font-semibold">
                    {{ session()->has('rentee') ? 'Welcome back to MDC' : 'Welcome to MDC' }}
                    <br>Property Reservation System
                </h1>
            </section>
            <footer class="flex justify-center p-4 ">

                <a href="{{ route('rentee.start-reservation') }}" class="button px-4 py-2 mb-4 bg-blue-500 rounded-full cursor-pointer select-none
    active:translate-y-2  active:[box-shadow:0_0px_0_0_#1b6ff8,0_0px_0_0_#1b70f841]
    active:border-b-[0px]
    transition-all duration-150 [box-shadow:0_4px_0_0_#1b6ff8,0_7px_0_0_#1b70f841]
    border-b border-blue-400
  ">
                    <span class="flex flex-col justify-center items-center h-full text-white font-bold text-lg ">
                        {{ session()->has('rentee') ? 'Back to Home' : 'Get started' }}</span>
                </a>

            </footer>

        </div>

    </div>

    <!-- Main Footer -->
    <footer class="w-full bg-white shadow-md fixed bottom-0 left-0">
        <div class="w-full flex py-2 justify-around">

            <div class="flex items-center space-x-2">
                <i class="fas fa-envelope text-blue-800"></i>
                <small class="text-blue-800 ">/mdc1983tub@gmail.com</small>
            </div>

            <div class="flex items-center space-x-2">
                <i class="fab fa-facebook text-blue-800"></i>
                <a href="https://www.facebook.com/mdctubigon"
                    class="text-blue-800 text-xs hover:underline">/mdctubigon</a>
            </div>




        </div>
    </footer>


</body>

</html>