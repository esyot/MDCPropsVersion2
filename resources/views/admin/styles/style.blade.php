@if($setting->darkMode == true)
    <style>
        #content {
            background-color: #000000;
        }

        #sidebar {
            background-color: #2d2d2d;
        }

        #topbar {
            background-color: #404040;
            color: white;
        }

        #topbar i {
            color: white;
        }

        #navbar {
            background-color: #404040;
            color: white;
        }

        #navbar h1 {
            color: #000;
        }

        #contacts {
            background-color: #1d1d1d;
        }

        #title {
            color: #fff;
        }

        #messages-container {
            background-color: #000;
        }

        #footer-messenger {
            background-color: #2d2d2d;
        }

        #header-messenger {
            background-color: #2d2d2d;
        }

        #sidebar-right {
            background-color: #2d2d2d;
            color: white;
        }

        #dropdownMenu {
            background-color: #6b7280;
            color: white;
        }

        #notification-dropdown {
            background-color: #6b7280;
            color: white;
        }

        #messages-dropdown {
            background-color: #6b7280;
            color: black;
        }

        #contact-list {
            color: white;
        }

        #user-dropdown {
            background-color: #6b7280;
            color: white;
        }

        #main-content {
            background-color: #000000;
        }

        #calendar-grid {
            background-color: #000;
        }

        footer {
            background-color: #000;
            color: #fff;
        }

        #dropdown-title {
            color: #fff;
        }

        #calendar-header {
            background-color: #202020;
        }

        #items-header {
            background-color: #1d1d1d;
        }

        #transactions-header {
            background-color: #1d1d1d;
        }

        #users-header {
            background-color: #1d1d1d;
        }

        #calendar {}
    </style>

@else

    <style>
        #calendar-header {
            background-color: #BBDEFB;
        }

        #items-header {
            background-color: #BBDEFB;
        }

        #transactions-header {
            background-color: #BBDEFB;
        }

        #users-header {
            background-color: #BBDEFB;
        }

        #topbar {}


        #chats {
            background-color: #f1f1f1;
        }

        #sidebar {

            background: linear-gradient(to bottom, #4f8ef7, #003366);
        }
    </style>

@endif
<style>
    /* Animation for opening */
    .animation-open {
        animation-name: fadeIn, zoomIn;
        animation-duration: 400ms, 400ms;
        animation-timing-function: ease, ease;
        animation-fill-mode: forwards;
    }

    /* Animation for closing */
    .animation-close {
        animation-name: fadeOut, zoomOut;
        animation-duration: 150ms, 150ms;
        animation-timing-function: ease, ease;
        animation-fill-mode: forwards;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }

    }

    @keyframes zoomIn {
        0% {
            transform: scale(0.1);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes zoomOut {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(0.9);
        }
    }
</style>

<style>
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

    .placeholder-center::placeholder {

        text-align: center;
    }

    .slider {
        position: relative;
        width: 60px;
        height: 32px;
    }

    .slider-track {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: background-color 0.3s;
        border-radius: 9999px;
    }

    .slider-thumb {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 28px;
        height: 28px;
        background-color: white;
        border-radius: 9999px;
        transition: transform 0.3s;
    }

    .slider input:checked+.slider-track {
        background-color: #2196F3;
    }

    .slider input:checked+.slider-track .slider-thumb {
        transform: translateX(26px);
    }
</style>