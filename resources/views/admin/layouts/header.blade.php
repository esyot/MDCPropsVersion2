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
    <link rel="icon" href="{{ asset('asset/logo/MDC-logo-clipped.png') }}" type="image/png">
    @include('admin.styles.header')

    @php
        if (!Auth::user()->isPasswordChanged && $page_title != 'Profile') {
            echo "<script>
                                                                                                                                                                                                            const redirectUrl = '" . route('profile') . "';
                                                                                                                                                                                                            window.location.href = redirectUrl;
                                                                                                                                                                                                        </script>";
        }
    @endphp



</head>

<body class="font-verdana flex h-screen overflow-hidden bg-gray-100 text-gray-800">
    <!-- Components -->
    @include('admin.components.rightbar')
    @include('admin.components.leftbar')
    @include('admin.components.topbar')


    <!-- contents -->
    @yield('content')

    <footer class="flex p-2 justify-center">
        <p>All rights reserved &copy; 2024</p>
    </footer>


    <script>
        (function () {


            document.body.addEventListener('htmx:beforeRequest', function () {
                document.getElementById('loader').classList.remove('hidden');
            });

            document.body.addEventListener('htmx:afterRequest', function () {
                document.getElementById('loader').classList.add('hidden');
            });

            function showModal(modalId) {
                if (modalId) {
                    document.getElementById(modalId).classList.remove('hidden');
                }
            }

            function hideModal(modalId) {
                if (modalId) {
                    document.getElementById(modalId).classList.add('hidden');
                }
            }



            notificationIcon.addEventListener('click', () => {
                notificationDropdown.classList.toggle('hidden');
            });

            userIcon.addEventListener('click', () => {
                userDropdown.classList.toggle('hidden');
            });

            messageIcon.addEventListener('click', () => {
                messageDropdown.classList.toggle('hidden');
            });

            document.addEventListener('DOMContentLoaded', function () {
                const seeMoreBtn = document.getElementById('see-more-btn');
                const notificationList = document.getElementById('notification-list');
                const notificationDropdown = document.getElementById('notification-dropdown');
                const messagesDropdown = document.getElementById('messages-dropdown');
                const userDropdown = document.getElementById('user-dropdown');
                const insideUser = document.getElementById('inside-user');
                const insideNotification = document.getElementById('inside-notification');
                const insideMessages = document.getElementById('inside-messages');

                if (seeMoreBtn) {
                    seeMoreBtn.addEventListener('click', function () {
                        if (notificationList.classList.contains('max-h-64')) {
                            notificationList.classList.remove('max-h-64');
                            notificationList.classList.add('max-h-[calc(100vh-8rem)]');
                            seeMoreBtn.textContent = 'See Less';
                        } else {
                            notificationList.classList.remove('max-h-[calc(100vh-8rem)]');
                            notificationList.classList.add('max-h-64');
                            seeMoreBtn.textContent = 'See More';
                        }
                    });
                }

                document.addEventListener('click', function (event) {
                    const clickedElement = event.target;

                    // Hide user dropdown if click is outside of it
                    if (!userDropdown.contains(clickedElement) && !insideUser.contains(clickedElement)) {
                        userDropdown.classList.add('hidden');
                    }

                    // Hide notification dropdown if click is outside of it
                    if (!notificationDropdown.contains(clickedElement) && !insideNotification.contains(clickedElement)) {
                        notificationDropdown.classList.add('hidden');
                    }

                    // Hide message dropdown if click is outside of it
                    if (!messagesDropdown.contains(clickedElement) && !insideMessages.contains(clickedElement)) {
                        messagesDropdown.classList.add('hidden');
                    }
                });

                const button = document.getElementById('dropdownButton');
                const menu = document.getElementById('dropdownMenu');
                const messagesButton = document.getElementById('dropdownMessages');

                button.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (event) {
                    if (!button.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });
        })();
    </script>