<style>
    @media(orientation: portrait) {

        #calendar-month-header div {
            font-size: 9px;
        }

        #calendar-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1;
            text-align: center;

        }

        #calendar-month-content div {
            font-size: 10px;

        }

        #calendar-months {
            height: 100%;

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

<div id="calendar-day-view">
</div>

<form id="filter-form" hx-get="{{ route('admin.date-custom')}}" hx-target="#dashboard" hx-swap="innerHTML"
    hx-trigger="change" class="select-none shadow-md">

    <div id="calendar-header" class="py-2">

        <div class="flex items-center space-x-1">
            <div class="flex items-center space-x-2 px-2">
                <a hx-get="{{ route('admin.calendar-move', ['category' => $currentCategory->id, 'action' => 'today', 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('m')]) }}"
                    hx-target="#dashboard" hx-swap="innerHTML"
                    class="px-4 py-2 bg-teal-500 text-teal-100 hover:opacity-50 shadow-md rounded-lg">
                    Today
                </a>

                <a hx-get="{{ route('admin.calendar-move', ['category' => $currentCategory->id, 'action' => 'left', 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('m')]) }}"
                    hx-target="#dashboard" hx-swap="innerHTML" class="drop-shadow">
                    <i class="fas fa-chevron-circle-left fa-2xl text-blue-500  hover:opacity-50"></i>
                </a>
                <a hx-get="{{ route('admin.calendar-move', ['category' => $currentCategory->id, 'action' => 'right', 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('m')]) }}"
                    hx-target="#dashboard" hx-swap="innerHTML" class="drop-shadow">
                    <i class="fas fa-chevron-circle-right fa-2xl text-blue-500  hover:opacity-50"></i>
                </a>
            </div>
            <div class="flex items-center hover:opacity-50 bg-white shadow-md p-2 rounded-lg">

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

            <div class="flex items-center hover:opacity-50 bg-white shadow-md p-2 rounded-lg">
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

    </div>

</form>



<div id="calendar-month" class="hidden w-full h-full overflow-hidden">

</div>


@php
    // Assuming $currentDate is a Carbon instance or a date string
    $currentDate = \Carbon\Carbon::parse($currentDate);
    $currentYear = $currentDate->year;
@endphp

<div id="calendar" class="">
    <div id="calendar-content" class="grid grid-cols-4 gap-1 m-1">
        {{-- Loop through each month --}}
        @foreach(range(1, 12) as $month)
                @php
                    // Create a Carbon instance for the first day of the current month
                    $monthStart = \Carbon\Carbon::createFromDate($currentYear, $month, 1);
                    $daysInMonth = $monthStart->daysInMonth;
                    $firstDayOfWeek = $monthStart->dayOfWeek;

                @endphp

                <div id="calendar-months" title="Click to select month"
                    hx-get="{{ route('admin.select-month', ['year' => $currentYear, 'month' => $month, 'category' => $currentCategory->id]) }}"
                    hx-target="#calendar-month" hx-swap="innerHTML" hx-trigger="click"
                    onclick="calendarSelectMonth({{ $month }})"
                    class="hover:opacity-50 rounded-t-lg bg-white shadow-md border cursor-pointer {{$setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : '' }}">

                    <div class="text-center bg-blue-600 text-white rounded-t-lg">
                        <span class="font-bold">{{ $monthStart->format('F') }}</span>
                    </div>

                    <div id="calendar-month-header" class="grid grid-cols-7 text-center bg-gray-100">
                        {{-- Days of the week header --}}
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class=" font-bold text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <div id="calendar-month-content" class="grid grid-cols-7">
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
                                        class=" {{ $hasRecord ? 'bg-gray-400 text-white' : '' }} {{ $currentDayInMonth == 'Sunday' ? 'text-red-500' : '' }} text-center ">
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

    function toggleReservationForm(day, transition) {
        const form = document.getElementById(`reservation-form-${day}`);
        const addButton = document.getElementById(`reservation-add-${day}`);

        if (transition) {
            form.classList.add('animation-open');
        }

        addButton.classList.remove('hidden');
    }


</script>