<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/main.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
</head>


<body>
    <div class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">
        <div class="bg-white mx-2 rounded shadow-md">
            <div class="flex justify-center items-center flex-col">
                <div class="py-1 rounded-t bg-blue-500 w-full">
                </div>
                <div class="shadow-xl mt-4 w-[100px] h-[100px] rounded-full">
                    <img src="{{ asset('asset/logo/logo.png') }}" alt="">
                </div>
                <div class="p-4">
                    <h1 class="text-lg text-center">
                        Sorry, you are not given permission <br> to access this page.
                    </h1>

                </div>
                <div class="p-4">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-blue-500 text-blue-100 rounded m-2 hover:opacity-50">
                        Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>