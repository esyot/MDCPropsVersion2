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
    <div class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded shadow-md">
            <div class="flex justify-center items-center flex-col">

                <div class="p-2 w-[100px] h-[100px] drop-shadow">
                    <img src="{{ asset('asset/logo/logo.png') }}" alt="">
                </div>
                <div class="p-4">
                    You are not allowed to view this page!
                </div>

                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 bg-blue-500 text-blue-100 rounded m-2 hover:opacity-50">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>

</html>