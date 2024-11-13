<form id="filter-form" action="{{ route('dateCustom')}}" method="GET" class="select-none">
    @csrf
    <div id="calendar-header" class="p-2">

        <div class="flex items-center">
            <div id="calendar-controls" class="flex space-x-2 p-2 ">

                <a title="Today"
                    hx-get="{{ route('calendarMove', ['action' => 'today', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="hidden cursor-pointer px-4 py-2 rounded-lg shadow-md text-teal-100 bg-teal-500 hover:bg-teal-800 hover:text-teal-100">
                    Today
                </a>

                <a title="Slide to left"
                    hx-get="{{ route('calendarMove', ['action' => 'left', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                    hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                    class="hidden shadow-md text-white fa-solid fa-chevron-left hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full"></a>
                </a>

                <a title="Slide to right"
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

<div id="calendar-month" class="hidden w-full h-full mb-20 flex flex-col justify-center items-center overflow-hidden">
    <header class="flex justify-center p-2 w-full bg-blue-500 ">
        <span class="text-white text-2xl text-center"> selectedMonth</span>
    </header>
    <div id="calendar-grid" class="grid grid-cols-7 gap-2 p-4 shadow-lg w-full h-full">

        <div
            class="rounded-t-lg calendar-cell bg-red-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Sun</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Mon</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Tue</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Wed</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Thu</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Fri</div>
        <div
            class="rounded-t-lg calendar-cell bg-gray-500 p-2 flex items-center justify-center font-semibold text-gray-100">
            Sat</div>

        <!-- Calculate the starting position of the first day -->
        @php
            $firstDayOfMonth = $currentDate->copy()->startOfMonth();
            $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
        @endphp

        <!-- Add empty cells for days before the first of the month -->
        @for ($i = 0; $i < $startDayOfWeek; $i++)
            <div class="calendar-cell bg-gray-300 p-4"></div>
        @endfor

        <!-- Generate calendar days -->
        @for ($day = 1; $day <= $currentDate->daysInMonth; $day++)
                @php
                    $currentDay = $currentDate->copy()->day($day)->format('Y-m-d');
                    $hasRecord = in_array($currentDay, $daysWithRecords);
                    $transactionItem = $transactions->firstWhere(function ($item) use ($currentDay) {
                        return \Carbon\Carbon::parse($item->rent_date)->format('Y-m-d') === $currentDay;
                    });
                    $date = $transactionItem ? \Carbon\Carbon::parse($transactionItem->rent_date)->format('Y-m-d') : null;
                    $isSunday = \Carbon\Carbon::parse($currentDay)->dayOfWeek === 0; // 0 for Sunday
                @endphp

                <button @if($hasRecord) hx-get="{{ $date ? route('dateView', ['date' => $date]) : '#' }}"
                hx-target="#modal-item" hx-swap="innerHTML" hx-trigger="click" @endif
                    class="{{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-90' : '' }} relative cursor-auto calendar-cell {{ $hasRecord == true ? 'bg-blue-500 text-white cursor-pointer shadow-md' : 'bg-gray-300' }} p-4 flex flex-col items-center justify-center font-semibold overflow-hidden group">
                    <div class="flex justify-center items-center">
                        <h1 class="drop-shadow font-bold text-4xl {{ $isSunday ? 'text-red-500' : '' }}">{{ $day }}</h1>
                    </div>
                    @if(!$hasRecord && !$roles->contains('viewer'))
                        <div onclick="toggleTransactionForm({{$day}}, {{$setting->transition}})" title="Add Transaction"
                            class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white opacity-0 bg-gray-500 {{ $setting->transitiona == true ? '' : 'group-hover:opacity-100 transition-opacity duration-300 ease-in-out'}}">
                            <h1 class="flex justify-center items-center text-4xl">+</h1>
                        </div>

                    @endif
                </button>
                @include('admin.modals.transaction-add')
        @endfor
    </div>
</div>

</form>

@php
    // Assuming $currentDate is a Carbon instance or a date string
    $currentDate = \Carbon\Carbon::parse($currentDate);
    $currentYear = $currentDate->year;
@endphp

<div id="calendar" class="p-2 flex h-full">
    <div class="grid grid-cols-6 gap-1">
        {{-- Loop through each month --}}
        @foreach(range(1, 12) as $month)
                @php
                    // Create a Carbon instance for the first day of the current month
                    $monthStart = \Carbon\Carbon::createFromDate($currentYear, $month, 1);
                    $daysInMonth = $monthStart->daysInMonth;
                    $firstDayOfWeek = $monthStart->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
                @endphp

                <div onclick="calendarSelectMonth({{$month}})"
                    class="border shadow-md transition-transform duration-300 ease-in-out hover:scale-90">
                    <div class="text-center bg-blue-600 text-white py-2">
                        <span class="">{{ $monthStart->format('F') }}</span>
                    </div>

                    <div class="grid grid-cols-7 gap-2 text-center bg-gray-100 p-2">
                        {{-- Days of the week header --}}
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="font-bold text-sm text-gray-700">{{ $day }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-2 p-2">
                        {{-- Render empty days at the beginning of the month --}}
                        @for ($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class=""></div>
                        @endfor

                        {{-- Render the days of the month --}}
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            <div
                                class="flex justify-center items-center rounded-full hover:bg-blue-100 cursor-pointer {{ $currentDate->isToday() && $currentDate->day == $day && $monthStart->month == $currentDate->month ? 'bg-yellow-500 text-white' : 'text-gray-700' }}">
                                {{ $day }}
                            </div>
                        @endfor

                        {{-- Render empty days at the end of the month --}}
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

        const calendar = document.getElementById('calendar');

        [...caldendar.children].forEach(item => {
            item.querySelector('a').classList.toggle('hidden');
        });




        document.getElementById('calendar-month').classList.remove('hidden');
        document.getElementById('calendar').classList.add('hidden');

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