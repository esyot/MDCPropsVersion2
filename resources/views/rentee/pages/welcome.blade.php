<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/dist/qrious.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/main.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header img {
            width: 70px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header .details {
            font-size: 0.9rem;
        }

        .footer-button {
            background-color: #2563eb;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .footer-button:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }

        .background-overlay {
            position: absolute;
            inset: 0;
            background: url('{{ asset('asset/background-img/mdc-back.jpg') }}') center/cover no-repeat;
            opacity: 0.4;
            z-index: -1;
        }
    </style>
</head>

<body>
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

        <div id="QR" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
            <div class="max-w-[300px] bg-white rounded-lg shadow-lg p-4 z-10 bg-gradient-to-b from-blue-100 to-blue-500">
                <h1 class="text-xl font-bold text-center">Reservation Successful!</h1>
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

                <div class="flex justify-center mt-2 ">
                    <button onclick="document.getElementById('QR').classList.add('hidden')"
                        class="footer-button">Done</button>
                </div>
            </div>
        </div>

    @endif

    <div class="flex fixed top-0 right-0 p-4 z-50">
        <a href="{{ route('tracking') }}" title="Check reservation"
            class="bg-blue-500 px-2 shadow-md py-2 rounded-full hover:opacity-50">
            <i class="fas fa-desktop fa-xl text-white shadow-md"></i>
        </a>
    </div>
    <div id="welcome" class="flex fixed inset-0 justify-center items-center">
        <div class="max-w-[600px] rounded-lg shadow-xl z-10 bg-white p-4">
            <header class="header">
                <img src="{{ asset('asset/logo/logo.png') }}" alt="MDC Logo">
                <div class="details">
                    <h1 class="text-2xl font-semibold">Mater Dei College</h1>

                    <p class="text-xs">
                        <i class="fas fa-directions text-red-500"></i> Brgy. Cabulijan, Tubigon, Bohol
                    </p>
                    <p class="text-xs">
                        <i class="fas fa-envelope text-gray-500"></i> mdc1983tub@gmail.com
                    </p>
                    <p>
                        <a href="https://www.facebook.com/mdctubigon" class="text-xs hover:underline text-blue-500">
                            <i class="fab fa-facebook"></i> mdctubigon
                        </a>
                    </p>
                </div>
            </header>
            <section class="text-center mt-6">
                <h1 class="text-2xl">
                    {{ session()->has('rentee') ? 'Welcome back to MDC' : 'Welcome to MDC' }}
                    <br>Property Reservation System
                </h1>
            </section>
            <footer class="flex justify-center mt-6">
                <a href="{{ route('rentee.start-reservation') }}" class="footer-button">
                    {{ session()->has('rentee') ? 'Back to Home' : 'Get started' }}
                </a>
            </footer>
        </div>
        <div class="background-overlay"></div>
    </div>
</body>

</html>