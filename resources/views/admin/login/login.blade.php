<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC-Property Rental & Reservation Management System</title>
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/MDC-logo-clipped.png') }}" type="image/png">
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
    </style>
</head>

<body class="bg-gradient-to-b from-blue-500 to-transparent">

    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-4xl flex flex-col md:flex-row container-height rounded-lg overflow-hidden">

            <!-- Container with the form -->

            <div class="shadow-md form-container bg-white flex-1 rounded-b-lg md:rounded-r-lg overflow-hidden p-4">
                <form action="{{ route('login') }}" class="w-full" method="POST">

                    @csrf
                    <div class="flex justify-center mb-4">
                        <h1 class="text-3xl font-bold">Welcome!</h1>
                    </div>



                    <div class="flex flex-col mt-4">

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
                                required>
                            <i
                                class="hover:bg-blue-800 absolute left-0 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-envelope"></i>
                        </div>
                        <div class="relative mb-4 mx-4">
                            <input id="password" name="password" type="password" placeholder="Input password"
                                class="shadow-inner w-full border-2 border-red-500 rounded-md py-2 pl-12 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                                required>
                            <i
                                class="hover:bg-red-800 hover:border-red-800 border border-transparent absolute left-0 top-1/2 transform -translate-y-1/2 bg-red-500 text-white rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-lock"></i>
                            <i id="togglePassword"
                                class="hover:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 cursor-pointer fas fa-eye-slash"></i>
                        </div>

                        <button type="submit"
                            class="mx-4 shadow-md px-4 py-2 bg-blue-500 hover:bg-blue-800 text-blue-100 rounded">Log
                            In</button>
                    </div>

                    <div class="flex justify-center mt-2">
                        <h1 class="text-sm">Forgot password? <a href="" class="hover:text-red-800 text-red-500">Reset
                                Password</a></h1>
                    </div>
                </form>
            </div>

            <!-- Container with the logo -->
            <div
                class="shadow-md logo-container flex flex-col bg-gradient-to-b from-blue-500 to-teal-500 flex-1 rounded-t-lg md:rounded-l-lg overflow-auto p-4">
                <div class="flex justify-center items-center flex-col h-full">
                    <img class="drop-shadow-md max-w-full h-auto mb-4"
                        src="{{ asset('asset/logo/MDC-Logo-Clipped.png')}}" height="200" width="200" alt="">
                    <h1 class="text-xl text-white text-center font-serif mb-4">MDC Property Rental & <br>
                        Reservation
                        Management <br> System</h1>
                    <h1 class="text-white text-sm">All rights reserved © 2024</h1>
                </div>
            </div>
        </div>
    </div>

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