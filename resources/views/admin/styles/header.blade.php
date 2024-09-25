@if($setting->darkMode == true)

    <!-- dark mode -->

    <style>
        #sidebar {
            background-color: #2d2d2d;
        }

        #navbar {
            background-color: #6b7280;
            color: white;
        }

        #navbar i {
            color: white;
        }

        #content {
            background-color: transparent;
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
    </style>

@else

    <style>
        #content {
            background: linear-gradient(to right, #00bcd4, #006064);

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