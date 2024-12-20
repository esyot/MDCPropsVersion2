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
    @include('admin.styles.dark-mode')

    @if (!Auth::user()->isPasswordChanged && $page_title != 'Profile')
        <script>
            const redirectUrl = '{{ route('profile') }}';
            window.location.href = redirectUrl;
        </script>
    @endif
    <style>
        /* Custom scrollbar styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Sidebar styles */
        .sidebar {}

        /* Navbar styles */
        .navbar {}

        /* Main content styles */
        .main-content {}

        .main-content.expanded {
            margin-left: 18rem;
        }

        .sidebar.expanded {
            width: 18rem;
        }

        .navbar.expanded {
            left: 18rem;
        }

        .placeholder-center::placeholder {
            text-align: center;
        }

        @media(orientation:landscape) {

            #footer {
                display: none;
            }

            #see-more-btn {
                display: none;
            }

            #footer-portrait {
                display: none;
            }

        }

        @media (orientation: portrait) {


            #sidebar {
                display: none;
            }

            #topbar-content {
                display: flex;
                justify-content: flex-end;
            }

            #notificationTitle {
                display: none;
            }

            #messageTitle {
                display: none;
            }

            #calendar-controls {
                display: none;
            }

            #custom-date-form {
                margin-right: 12px;
            }

            #footer-landscape {
                display: none;
            }


        }
    </style>

</head>

<body class="font-verdana flex h-screen overflow-hidden bg-gray-100 text-gray-800">

    @include('admin.components.rightbar')

    @hasrole('superadmin|admin|staff')

    @include('admin.components.leftbar')

    @endhasrole

    @include('admin.components.topbar')

    <!-- contents -->
    @yield('content')


</body>

</html>