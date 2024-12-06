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

        #notification-dropdown #title {

            color: white;
        }

        #topbar-content i {
            color: white;
        }

        #messages-dropdown {
            background-color: #6b7280;
            color: black;
        }

        #contact-list {
            color: white;
        }

        #main-content {
            background-color: #000000;
        }

        #calendar-grid {
            background-color: #000;
        }

        #footer-landscape {
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

        #reservation-to-return-header {
            background-color: #1d1d1d;
        }

        #reservation-to-claim-header {
            background-color: #1d1d1d;
        }

        #messages-dropdown #title {
            color: #fff;
        }


        #user-dropdown {
            color: #fff;
        }

        #user-dropdown-content {
            background-color: #6b7280;
        }

        #analytics-header {
            background-color: #1d1d1d;
            color: #fff;
        }

        #footer-portrait {
            background-color: #1d1d1d;
            color: #fff;
        }
    </style>

@else

    <style>
        #analytics-header {
            background-color: #BBDEFB;
        }

        #user-dropdown-content {
            background-color: #fff;
        }

        #calendar-header {
            background-color: #BBDEFB;
        }

        #reservation-to-claim-header {
            background-color: #BBDEFB;
        }

        #reservation-to-return-header {
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