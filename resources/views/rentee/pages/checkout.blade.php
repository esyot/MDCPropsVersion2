<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('asset/css/flatpickr.min.css')}}">
    <script src="{{asset('asset/js/flatpickr.min.js')}}"></script>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/photos/logo.png') }}" type="image/png">

    <!-- JavaScript Libraries -->
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Checkout</title>
</head>
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
</style>

<body class="bg-gray-100 h-screen">

    <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h2 class="text-xl font-semibold text-red-600 mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i> Error
            </h2>
            <p class="text-gray-700 mb-4">Please complete the input fields first before proceeding to checkout.</p>
            <div class="flex justify-end">
                <button class="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600"
                    onclick="document.getElementById('error-modal').classList.add('hidden')">Close</button>
            </div>
        </div>
    </div>


    <header
        class="flex items-center space-x-2 z-50 bg-gradient-to-r from-blue-400 to-blue-800 p-2 shadow-md sticky top-0">
        <a href="{{ route('rentee.back-to-home', ['rentee' => $rentee]) }}" class="hover:opacity-50">
            <i class="fas fa-arrow-circle-left fa-xl text-white"></i>
        </a>
        <h1 class="text-xl text-white font-bold">{{$page_title}}</h1>
    </header>


    @if ($errors->any())
        <div class="bg-red-500 text-white p-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form id="reservation-form" action="{{ route('rentee.reservation-add', ['rentee' => $rentee]) }}" method="POST">
        @csrf
        <div id="rentee-details"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-[500px] mx-2">
                <div class="flex items-center justify-between bg-blue-500 rounded-t-lg px-2 text-white">
                    <h1 class="text-xl font-bold">Input Details</h1>
                    <button class="text-2xl font-bold hover:opacity-50"
                        onclick="document.getElementById('rentee-details').classList.add('hidden')">&times;</button>
                </div>


                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-100 border-b-2">
                    <div class="mb-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name:</label>
                        <input type="text" name="name" placeholder="Enter First Name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>


                    <div class="mb-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" name="email" placeholder="Enter Email"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>

                    <div class="mb-2">
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact #:</label>
                        <input type="number" name="contact_no" placeholder="Enter Contact #"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="block text-sm font-medium text-gray-700">Address:</label>
                        <input type="text" name="address" placeholder="Enter Address"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>


                </div>
                <div class="p-2 border-b-2">
                    <div>
                        <h1 class="text-xl font-bold">Terms & Conditions</h1>
                    </div>
                    <div class="flex text-xs space-x-1">
                        <input id="terms-checkbox" type="checkbox" name="" id="" class="hidden" required>
                        <div class="">
                            <span id="terms-checkbox-label-1">
                                Read first the
                            </span>
                            <span id="terms-checkbox-label-2" class="hidden">
                                I have read & accept the website's
                            </span>

                            <button type="button"
                                onclick="document.getElementById('terms-and-conditions').classList.remove('hidden')"
                                class="text-blue-500 hover:opacity-50 underline">Terms &
                                Conditions.
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-2 flex justify-end">
                    <button type="submit" id="submit-btn"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 hidden">Submit
                        Reservation</button>
                </div>
            </div>
        </div>

        <div id="terms-and-conditions"
            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
            <div class="bg-white w-[500px] rounded-md custom-scrollbar shadow-md mx-2">
                <div class="border-b-2 p-2  h-[60%] overflow-y-auto">


                    <h1 class="text-xl font-bold">Terms & Conditions</h1>
                    <p class="text-justify">By using this website, you agree to these Terms and Conditions. All listed
                        Properties are for rent and borrowing only, with no ownership rights conveyed. Services are
                        provided "as is." without guarantees, and we reserve the right to modify or discontinue any
                        part of the site at any time.
                        You are responsible for accurate information and ensuring rented items are returned on
                        time, and in the same condition. Any damage or costs related to rented properties are the
                        renter's responsibility. Reservations have an expiration date; once not
                        paid or claimed on time, it will automatically cancel your reservation. and please be reminded
                        that QR codes are very
                        important; once you lose it, you will not be able to proceed with your reservation.

                    </p>
                    <h1 class="text-xl font-bold mt-2">Privacy Notice</h1>
                    <p>
                        We value your privacy and are committed to protecting your personal data in accordance with the
                        Data
                        Privacy Act of 2012 (Republic Act No. 10173). By using this website, you consent to the
                        collection,
                        use, and processing of your personal information as described in our Privacy Policy. We will
                        take
                        all reasonable steps to protect your personal data and ensure it is handled securely.
                    </p>

                </div>
                <div class="p-2 flex justify-end">
                    <button type="button" onclick="updateTermsState()"
                        class="px-4 py-2 border border-gray-300 hover:opacity-50 shadow-md rounded">Done</button>

                </div>


            </div>

        </div>

        <script>
            function updateTermsState() {

                document.getElementById('terms-and-conditions').classList.add('hidden');
                document.getElementById('submit-btn').classList.remove('hidden');
                document.getElementById('terms-checkbox').classList.remove('hidden');
                document.getElementById('terms-checkbox').checked = true;
                document.getElementById('terms-checkbox-label-1').classList.add('hidden');
                document.getElementById('terms-checkbox-label-2').classList.remove('hidden');
            }
        </script>


        <div class="flex overflow-x-auto w-full">


            @foreach ($properties as $property)

                        <div class="bg-white flex flex-col p-2 border m-2 rounded transition-transform duration-300 hover:scale-90">
                            <div onclick="openCalendar({{ $property->id }})" class="flex w-[120px] justify-between cursor-pointer">
                                <div>
                                    <input type="hidden" name="properties[{{ $property->id }}][property_id]"
                                        value="{{ $property->id }}">
                                    <img src="{{ asset('storage/images/categories/' . $property->category->folder_name . '/' . $property->img) }}"
                                        alt="{{ $property->name }}"
                                        class="w-16 h-16 object-cover rounded-md border border-gray-300 shadow-md">
                                    <p class="text-gray-800 text-lg font-medium">{{ $property->name }}</p>
                                </div>
                            </div>
                            <div>


                                <label for="quantity" class="text-sm font-medium text-gray-700">Piece/s:</label>
                                <input type="number" max="{{ $property->qty }}" name="properties[{{ $property->id }}][quantity]"
                                    value='1' min="1" class="p-2 border border-gray-300 w-full rounded"
                                    placeholder="Available: {{ $property->qty }}" required>
                            </div>
                        </div>

                        <script>
                            document.querySelector('input[name="properties[{{ $property->id }}][quantity]"]').addEventListener('input', function () {
                                const max = parseInt(this.getAttribute('max'));
                                if (this.value > max) {
                                    this.value = max;
                                }
                            });
                        </script>
                        @include('rentee.modals.preview-date', [
                    $properties
                ])

            @endforeach
        </div>
        <section id="property-details" class="p-4 bg-white m-4 shadow-md rounded-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    @include('map')
                </div>
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700">Date Start:</label>
                    <input type="text" id="date_start" name="date_start" placeholder="Date Start"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label for="time_start" class="block text-sm font-medium text-gray-700">Time Start:</label>
                    <input type="time" id="time_start" name="time_start"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700">Date End:</label>
                    <input type="text" id="date_end" name="date_end" placeholder="Date End"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label for="time_end" class="block text-sm font-medium text-gray-700">Time End:</label>
                    <input type="time" id="time_end" name="time_end"
                        class="p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <script>
                    const unavailableDateRanges = @json($unavailableDateRanges);

                    function isDateUnavailable(date) {
                        const formattedDate = formatDate(date);
                        return unavailableDateRanges.some(range => {
                            const startDate = formatDate(range.start);
                            const endDate = formatDate(range.end);
                            return formattedDate >= startDate && formattedDate <= endDate;
                        });
                    }

                    function formatDate(date) {
                        const d = new Date(date);
                        const year = d.getFullYear();
                        const month = ('0' + (d.getMonth() + 1)).slice(-2);
                        const day = ('0' + d.getDate()).slice(-2);
                        return `${year}-${month}-${day}`;
                    }

                    let startDatePicker = flatpickr("#date_start", {
                        minDate: "today",
                        disable: [isDateUnavailable],
                        onChange: function (selectedDates) {
                            if (selectedDates.length > 0) {
                                const startDate = selectedDates[0];
                                endDatePicker.set('minDate', startDate);
                                endDatePicker.open();
                            } else {
                                endDatePicker.close();
                            }
                        },
                        clickOpens: true,
                    });

                    let endDatePicker = flatpickr("#date_end", {
                        minDate: "today",
                        disable: [
                            isDateUnavailable,
                            function (date) {
                                const startDate = startDatePicker.selectedDates[0];
                                if (startDate) {
                                    return date < startDate || isDateInRange(startDate, date);
                                }
                                return false;
                            }
                        ],
                        clickOpens: true,
                    });

                    function isDateInRange(startDate, endDate) {
                        return unavailableDateRanges.some(range => {
                            const rangeStart = new Date(range.start);
                            const rangeEnd = new Date(range.end);
                            return (startDate <= rangeStart && endDate >= rangeEnd);
                        });
                    }

                    document.getElementById('date_start').addEventListener('input', function () {
                        const dateStartValue = document.getElementById('date_start').value;
                        const dateEndInput = document.getElementById('date_end');

                        if (!dateStartValue) {
                            dateEndInput.setAttribute('readonly', true);
                            endDatePicker.close();
                        } else {
                            dateEndInput.removeAttribute('readonly');
                            endDatePicker.open();
                        }
                    });

                    function validateTime() {
                        const dateStart = document.getElementById('date_start').value;
                        const dateEnd = document.getElementById('date_end').value;
                        const timeStart = document.getElementById('time_start').value;
                        const timeEnd = document.getElementById('time_end').value;

                        if (dateStart && dateEnd && dateStart === dateEnd) {
                            if (timeEnd && timeStart && timeEnd < timeStart) {
                                alert("The time end must be ahead of the time Start on the same date.");
                                document.getElementById('time_end').value = '';
                                document.getElementById('time_start').value = '';
                                document.getElementById('time_end').setCustomValidity("Time End must be greater than or equal to Time Start.");
                            } else {
                                document.getElementById('time_end').setCustomValidity("");
                            }
                        } else {
                            document.getElementById('time_end').setCustomValidity("");
                        }
                    }

                    document.getElementById('time_start').addEventListener('input', validateTime);
                    document.getElementById('time_end').addEventListener('input', validateTime);
                    document.getElementById('date_start').addEventListener('input', validateTime);
                    document.getElementById('date_end').addEventListener('input', validateTime);
                </script>


                <div>
                    <label for="">Reservation Type: </label>
                    <select name="reservation_type" id="reservation_type"
                        class="block p-2 w-full border border-gray-300 rounded">
                        <option value="rent">Rent</option>
                        <option value="borrow">Borrow</option>
                    </select>
                </div>
                <div>
                    <label for="" class="block text-sm font-medium text-gray-700">Purpose:</label>
                    <textarea name="purpose" id="purpose" placeholder="Input purpose..."
                        class="w-full border border-gray-300 rounded"></textarea>

                </div>

            </div>
            <div class="flex w-full justify-center">
                <button type="button" onclick="validateInputs()"
                    class="px-6 py-3 bg-blue-500 text-blue-100 shadow-md rounded hover:opacity-50">Next</button>
            </div>
        </section>
        <script>

            function check() {
                var formData = {};

                var elements = document.querySelectorAll('input, select, textarea');


                elements.forEach(function (element) {
                    if (element.name) {
                        formData[element.name] = element.value;
                    }
                });

                localStorage.setItem('formData', JSON.stringify(formData));

            }

            document.querySelectorAll('input, select, textarea').forEach(function (element) {
                element.addEventListener('input', check);
            });


            window.addEventListener('load', function () {
                var storedData = localStorage.getItem('formData');

                if (storedData) {
                    var formData = JSON.parse(storedData);


                    Object.keys(formData).forEach(function (key) {
                        var element = document.querySelector(`[name="${key}"]`);
                        if (element) {
                            element.value = formData[key];
                        }
                    });

                }
            });


        </script>



    </form>

    <script>


        function validateInputs() {
            const itemInputs = document.querySelectorAll('#property-details input');
            for (const input of itemInputs) {
                if (input.value.trim() === '') {
                    document.getElementById('error-modal').classList.remove('hidden')
                    return false;
                }
            }
            document.getElementById('rentee-details').classList.remove('hidden');
            return true;
        }


        const unavailableDates = {};

        async function fetchUnavailableDates(itemId) {
            try {
                const res = await fetch(`/rentee/property/${itemId}`);
                if (res.ok) {
                    const data = await res.json();

                    unavailableDates[itemId] = data.map(date => convertToLocalDate(date));
                } else {
                    unavailableDates[itemId] = [];
                }
            } catch (err) {
                unavailableDates[itemId] = [];
            }
            updateCalendar(itemId);
        }
        function convertToLocalDate(dateStr) {
            const date = new Date(dateStr);
            const localYear = date.getFullYear();
            const localMonth = String(date.getMonth() + 1).padStart(2, '0');
            const localDay = String(date.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`;
        }


        function openCalendar(itemId) {
            fetchUnavailableDates(itemId);
            document.getElementById(`date-${itemId}`).classList.remove('hidden');
        }


        function changeMonth(itemId, dir) {
            const monthInput = document.getElementById(`month-input-${itemId}`);
            const date = new Date(monthInput.value + '-01');

            if (dir === 'right') date.setMonth(date.getMonth() + 1);
            if (dir === 'left') date.setMonth(date.getMonth() - 1);
            if (dir === 'today') date.setFullYear(new Date().getFullYear(), new Date().getMonth());

            monthInput.value = date.toISOString().slice(0, 7);
            updateCalendar(itemId);
        }


        function getLocalDate() {
            const today = new Date();
            const localYear = today.getFullYear();
            const localMonth = String(today.getMonth() + 1).padStart(2, '0');
            const localDay = String(today.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`;
        }


        function updateCalendar(itemId) {
            const [y, m] = document.getElementById(`month-input-${itemId}`).value.split('-').map(Number);
            const daysInMonth = new Date(y, m, 0).getDate();
            const calendarContainer = document.getElementById(`calendar-${itemId}`);
            const unavailableDays = new Set((unavailableDates[itemId] || []));

            const todayString = getLocalDate();

            calendarContainer.innerHTML = '';

            Array.from({ length: new Date(y, m - 1).getDay() }).forEach(() => calendarContainer.appendChild(document.createElement('div')));


            for (let day = 1; day <= daysInMonth; day++) {
                const currentDay = new Date(y, m - 1, day);
                const localDayString = getLocalDateFromDate(currentDay);

                const dayDiv = document.createElement('div');
                dayDiv.innerText = day;
                dayDiv.className = 'flex justify-center shadow-md items-center h-10 w-10 bg-white rounded border relative';


                if (currentDay.getDay() === 0) dayDiv.classList.add('text-red-500');


                if (localDayString === todayString) {
                    dayDiv.classList.add();


                    const circle = document.createElement('i');
                    circle.className = 'fas fa-circle text-green-500 text-[5px] absolute bottom-1 z-50 left-1/2 transform -translate-x-1/2';
                    dayDiv.appendChild(circle);
                }


                if (unavailableDays.has(localDayString)) {
                    dayDiv.classList.add('bg-gray-300', 'text-gray-400');
                    dayDiv.style.pointerEvents = 'none';
                }

                calendarContainer.appendChild(dayDiv);
            }
        }

        function getLocalDateFromDate(date) {
            const localYear = date.getFullYear();
            const localMonth = String(date.getMonth() + 1).padStart(2, '0');
            const localDay = String(date.getDate()).padStart(2, '0');
            return `${localYear}-${localMonth}-${localDay}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            @foreach($properties as $property)
                updateCalendar('{{$property->id}}');
            @endforeach
        });

        window.openCalendar = openCalendar;
        window.changeMonth = changeMonth;


        // LocalStorage handling
        function saveFormData() {
            const formData = {
                time_end: document.getElementById('time_end').value,
                time_start: document.getElementById('time_start').value,
                date_start: document.getElementById('date_start').value,
                date_end: document.getElementById('date_end').value,
                reservation_type: document.getElementById('reservation_type').value,
                purpose: document.getElementById('purpose').value
            };

            console.log(formData);

            // Save to localStorage
            localStorage.setItem('formFields', JSON.stringify(formData));
        }

        // Populate form fields with data from localStorage
        function populateFormData() {
            const savedFormData = localStorage.getItem('formFields');
            if (savedFormData) {
                const formData = JSON.parse(savedFormData);

                // Set the form fields with the saved data
                document.getElementById('time_end').value = formData.time_end;
                document.getElementById('time_start').value = formData.time_start;
                document.getElementById('date_start').value = formData.date_start;
                document.getElementById('date_end').value = formData.date_end;
                document.getElementById('reservation_type').value = formData.reservation_type;
                document.getElementById('purpose').value = formData.purpose;
            }
        }




        // Initialize the form data population when the page loads
        window.addEventListener('DOMContentLoaded', function () {
            populateFormData();
        });

    </script>




</body>

</html>