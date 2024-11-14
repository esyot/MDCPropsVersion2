<form id="filter-form" action="{{ route('dateCustom')}}" method="GET" class="select-none">
    @csrf
    <div id="calendar-header" class="p-2">

        <div class="flex items-center">
            <div id="calendar-controls" class="flex space-x-2 p-2">
                <a id="today-btn" title="Today"
                    hx-get="{{ route('calendarMove', ['action' => 'today', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="hidden cursor-pointer px-4 py-2 rounded-lg shadow-md text-teal-100 bg-teal-500 hover:bg-teal-800 hover:text-teal-100">
                    Today
                </a>

                <a id="slide-left-btn" title="Slide to left"
                    hx-get="{{ route('calendarMove', ['action' => 'left', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="hidden shadow-md text-white fa-solid fa-chevron-left hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full">
                </a>

                <a id="slide-right-btn" title="Slide to right"
                    hx-get="{{ route('calendarMove', ['action' => 'right', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="hidden shadow-md text-white fa-solid fa-chevron-right hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full">
                </a>
            </div>


            <div class="flex space-x-2 justify-center hidden">

                <a title="Today" class="hidden"
                    hx-get="{{ route('calendarMove', ['action' => 'today', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="cursor-pointer px-4 py-2 rounded-lg shadow-md text-teal-100 bg-teal-400 hover:bg-teal-600">
                    Today
                </a>

                <a title="Slide to left" class="hidden"
                    hx-get="{{ route('calendarMove', ['action' => 'left', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard">

                    <i
                        class="shadow-md text-white fa-solid fa-chevron-left hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full"></i>
                </a>

                <a title="Slide to right" class="hidden"
                    hx-get="{{ route('calendarMove', ['action' => 'right', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard">
                    <i
                        class="shadow-md text-white fa-solid fa-chevron-right hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full"></i>
                </a>

            </div>

            <div class="flex space-x-1">

                <div title="Month" class="hidden flex items-center bg-white shadow-md p-2 rounded-lg">

                    <i id="month-icon" class="fas fa-calendar text-gray-500"></i>
                    <select name="month" class="bg-transparent focus:outline-none">
                        <option class="text-red-500 font-semibold" value="">
                            {{ $currentDate->format('F') }}
                        </option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>

                <div>

                    <div title="Category" class="flex items-center bg-white shadow-md p-2 rounded-lg">
                        <i id="category-icon" class="fa-solid fa-calendar-days text-gray-500"></i>
                        <select name="year" class="bg-transparent focus:outline-none">
                            <option class="text-red-500 font-semibold" value="{{ $currentDate->format('Y') }}">
                                {{ $currentDate->format('Y') }}
                            </option>
                            @php
                                $years = range(2024, 2050); 
                            @endphp

                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div title="Category" class="flex items-center bg-white shadow-md p-2 rounded-lg">
                    <i id="category-icon" class="fa-solid fa-list text-gray-500"></i>

                    <select name="category"
                        class="bg-transparent focus:outline-none w-full overflow-hidden text-ellipsis whitespace-nowrap">

                        <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                            {{ $currentCategory->title }}
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->title }}
                            </option>

                        @endforeach

                    </select>
                </div>
            </div>

</form>
</div>
</div>

<style>
    @media(orientation: portrait) {

        #calendar {
            height: 50%;
        }

    }

    @media(orientation: landscape) {

        #calendar {
            height: 90%;
        }

    }

    .calendar-cell {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>


<div id="modal-item"></div>

<div id="calendar-month" class="hidden w-full h-full flex flex-col justify-center items-center overflow-hidden">

</div>


@php
    // Assuming $currentDate is a Carbon instance or a date string
    $currentDate = \Carbon\Carbon::parse($currentDate);
    $currentYear = $currentDate->year;
@endphp

<div id="calendar" class="">
    <div id="calendar-content" class="grid grid-cols-4 gap-2 px-2 pt-2">
        {{-- Loop through each month --}}
        @foreach(range(1, 12) as $month)
                @php
                    // Create a Carbon instance for the first day of the current month
                    $monthStart = \Carbon\Carbon::createFromDate($currentYear, $month, 1);
                    $daysInMonth = $monthStart->daysInMonth;
                    $firstDayOfWeek = $monthStart->dayOfWeek;

                @endphp

                <div title="Click to select month"
                    hx-get="{{ route('admin.select-month', ['year' => $currentYear, 'month' => $month, 'category' => $currentCategory]) }}"
                    hx-target="#calendar-month" hx-swap="innerHTML" hx-trigger="click"
                    onclick="calendarSelectMonth({{ $month }})"
                    class="flex rounded-t-lg flex-col bg-white border h-[190px] shadow-md cursor-pointer {{$setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : '' }}">

                    <div class="text-center bg-blue-600 text-white rounded-t-lg">
                        <span>{{ $monthStart->format('F') }}</span>
                    </div>

                    <div class="grid grid-cols-7 gap-0 text-center bg-gray-100">
                        {{-- Days of the week header --}}
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="font-bold text-sm text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-0">
                        {{-- Render empty days at the beginning of the month --}}
                        @for ($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class=""></div>
                        @endfor


                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = \Carbon\Carbon::createFromDate($currentYear, $month, $day);
                                        $currentDay = $date->format('Y-m-d');
                                        $currentDayInMonth = $date->format('l');
                                        $hasRecord = in_array($currentDay, $daysWithRecords); 
                                    @endphp

                                    <div
                                        class="{{ $hasRecord ? 'bg-gray-400 text-white' : '' }} {{ $currentDayInMonth == 'Sunday' ? 'text-red-500' : '' }} text-center ">
                                        <span class="relative inline-block">
                                            @if(\Carbon\Carbon::now()->isToday() && \Carbon\Carbon::now()->day == $day && $monthStart->month == \Carbon\Carbon::now()->month && $currentYear == \Carbon\Carbon::now()->format('Y'))
                                                <i class="fas fa-circle text-green-500 text-[4px] absolute top-5 left-0 right-0 z-50"></i>
                                            @endif
                                            <span>{{$day}}</span>
                                        </span>

                                    </div>


                        @endfor


                        @for ($i = $monthStart->addDays($daysInMonth)->dayOfWeek; $i < 7; $i++)
                            <div class=""></div>
                        @endfor
                    </div>
                </div>
        @endforeach

    </div>
</div>



<script>

    function calendarSelectMonth(month) {


        document.getElementById('calendar-header').classList.toggle('hidden');

        document.getElementById('calendar-month').classList.toggle('hidden');
        document.getElementById('calendar').classList.toggle('hidden');

    }

    function toggleTransactionForm(day, transition) {
        const form = document.getElementById(`transaction-form-${day}`);
        const addButton = document.getElementById(`transaction-add-${day}`);

        if (transition) {
            form.classList.add('animation-open');
        }

        addButton.classList.remove('hidden');
    }

    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>