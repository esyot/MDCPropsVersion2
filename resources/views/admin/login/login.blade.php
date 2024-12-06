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
        @media (max-width: 768px) {
            .container-height {
                max-height: calc(100vh - 4rem);
                /* Ensure the container height fits within the viewport with added margin */
                margin: 2rem;
                /* Add margins on all sides */
            }

            .form-container {
                order: 2;
                /* Move the form container to the bottom */
            }

            .logo-container {
                order: 1;
                /* Move the logo container to the top */
            }
        }

        @media (min-width: 768px) {
            .container-height {
                height: calc(80vh - 2rem);
                /* Adjust height on larger screens */
                margin: 2rem;
                /* Add margins on all sides */
            }

            .form-container {
                order: 1;
                /* Ensure the form container is first on larger screens */
            }

            .logo-container {
                order: 2;
                /* Ensure the logo container is second on larger screens */
            }
        }

        .form-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Center vertically */
            align-items: center;
            /* Center horizontally */
            height: 100%;
        }

        .form-container form {
            width: 100%;
            /* Make form take up full width of container */
            max-width: 400px;
            /* Optional: Set a max-width for better layout control */
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        @media(orientation: portrait) {

            #app-title {
                display: none;
            }


        }
    </style>
</head>

<body class="overflow-hidden bg-gradient-to-b from-blue-500 to-transparent">
    @include('admin.partials.success.success-modal')
    @include('admin.partials.errors.error-modal')

    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-4xl flex flex-col md:flex-row container-height rounded-lg overflow-hidden">

            <!-- Container with the form -->

            <div class="shadow-md form-container bg-white flex-1 rounded-b-lg md:rounded-r-lg overflow-hidden p-4">
                <form action="{{ route('login') }}" class="w-full" method="POST">

                    @csrf
                    <div class="flex justify-center mb-4">
                        <h1 class="text-3xl font-bold">Welcome!</h1>
                    </div>



                    <div class="flex flex-col">

                        <div class="relative mx-4">
                            <!-- Error Message Section -->
                            @if ($errors->any())
                                <div class="flex space-x-1 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                                    role="alert">
                                    <span>
                                        <i class="fas fa-exclamation-circle text-red-500" role="button"></i>

                                    </span>
                                    <span class="block sm:inline">{{ $errors->first() }}</span>

                                </div>
                            @endif
                        </div>
                        <div class="relative mb-4 mx-4">
                            <input type="text" name="email" placeholder="Input email"
                                class="shadow-inner pl-12 pr-12 w-full border-2 border-blue-500 rounded-md py-2 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                                value="{{ session('username') ?? old('email') }}" required>
                            <i class=" hover:bg-blue-800 absolute left-0 top-1/2 transform -translate-y-1/2
                                bg-blue-500 text-white rounded-l-md py-3 px-3 transition-colors duration-300 fas
                                fa-envelope"></i>
                        </div>
                        <div class="relative mb-4 mx-4">
                            <input id="password" name="password" type="password" placeholder="Input password"
                                class="shadow-inner w-full border-2 border-red-500 rounded-md py-2 pl-12 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                                value="{{old('password') }}" required>

                            <i
                                class="hover:bg-red-800 hover:border-red-800 border border-transparent absolute left-0 top-1/2 transform -translate-y-1/2 bg-red-500 text-white rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-key"></i>
                            <i id="togglePassword"
                                class="hover:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 cursor-pointer fas fa-eye-slash"></i>
                        </div>

                        <button type="submit"
                            class="mx-4 shadow-md px-4 py-2 bg-blue-500 hover:bg-blue-800 text-blue-100 rounded">Log
                            In</button>
                    </div>

                    <div class="flex justify-center mt-2">
                        <h1 class="text-xs">Forgot password? <button type="button"
                                onclick="document.getElementById('password-reset-request-form').classList.toggle('hidden')"
                                class="hover:text-red-800 text-red-500">Request
                                password reset. </button>
                        </h1>
                    </div>
                </form>
            </div>
            <!-- Container with the logo -->
            <div
                class="shadow-md logo-container flex flex-col bg-gradient-to-b from-blue-500 to-teal-500 flex-1 rounded-t-lg md:rounded-l-lg overflow-auto p-4">
                <div class="flex justify-center items-center flex-col h-full">
                    <img class="drop-shadow-md max-w-full h-auto mb-4" src="{{ asset('asset/logo/logo.png')}}"
                        height="150" width="150" alt="">
                    <h1 id="app-title" class="text-xl text-white text-center font-serif mb-4">MDC Property
                        Reservation <br> Management System</h1>
                    <h1 id="app-copy" class="text-white text-sm">All rights reserved © 2024</h1>
                </div>
            </div>
        </div>
    </div>


    <form action="{{route('admin.password-reset-request')}}" method="POST" id="password-reset-request-form"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        @csrf
        <section class="bg-white rounded w-[400px] shadow-md">
            <header class=" flex items-center justify-between py-1 bg-blue-500 rounded-t">
                <h1 class="p-2 text-lg text-white">Request Password Reset</h1>
                <button onclick="document.getElementById('password-reset-request-form').classList.toggle('hidden')"
                    class="px-2 text-white text-2xl hover:opacity-50">&times;</button>

            </header>
            <div class="p-2 border-b">
                <label for="" class="font-medium">Email:</label>
                <input type="email" name="email" placeholder="Input email Ex. example@gmail.com"
                    class="block p-2 border border-gray-300 w-full rounded" required>
                <small><strong>Note:</strong> Please allow 1-2 business days for processing your password reset request.
                    Thank you for your patience.</small>


            </div>

            <footer class="flex justify-end p-2 bg-gray-100 rounded-b">
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Submit</button>
            </footer>
        </section>
    </form>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>

</body>

</html>