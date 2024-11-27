<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>


<body>
    <div class="flex fixed inset-0 justify-center items-center bg-gray-900 bg-opacity-70">
        <div class="bg-gradient-to-b from-red-500 to-red-800 p-2 mx-2 rounded shadow-md">
            <div class="flex justify-center items-center flex-col">

                <div class="border-2 border-red-800 w-[100px] h-[100px] rounded-full shadow-md">
                    <img src="{{ asset('asset/logo/logo.png') }}" alt="">
                </div>
                <div class="p-4 ">
                    <h1 class="text-lg text-center text-red-100">
                        You are not given permission to access this page.
                    </h1>

                </div>
                <div class="p-4">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-red-500 text-red-100 rounded m-2 hover:opacity-50">
                        Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>