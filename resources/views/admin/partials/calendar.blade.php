<style>
    #calendar {
        position: relative;
        overflow: hidden;

    }


    #calendar-grid {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform-origin: top left;

        transform: scale(calc(100vh / 600px), calc(100vw / 1200px));

    }
</style>

<div id="navbar" class="flex items-center justify-between p-4 shadow-md">
    <div class="flex items-center space-x-2">
        <form id="filter-form" class="flex justify-around space-x-4" action="{{ route('dateCustom')}}" method="GET">
            @csrf


            <a title="Today"
                hx-get="{{ route('calendarMove', ['action' => 'today', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard"
                class="cursor-pointer px-4 py-2 rounded-lg shadow-md text-teal-100 bg-teal-400 hover:bg-teal-600 transition-transform duration-300 ease-in-out transform hover:scale-110">
                Today
            </a>

            <a title="Slide to left"
                hx-get="{{ route('calendarMove', ['action' => 'left', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard">

                <i
                    class="shadow-md text-white fa-solid fa-chevron-left hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
            </a>

            <a title="Slide to right"
                hx-get="{{ route('calendarMove', ['action' => 'right', 'category' => $currentCategory->id, 'year' => $currentDate->format('Y'), 'month' => $currentDate->format('n')])}}"
                hx-trigger="click" hx-swap="innerHTML" hx-target="#dashboard">
                <i
                    class="shadow-md text-white fa-solid fa-chevron-right hover:text-blue-300 cursor-pointer bg-blue-500 w-10 h-10 flex items-center justify-center rounded-full transition-transform duration-300 ease-in-out transform hover:scale-110"></i>
            </a>



            <div title="Month"
                class="shadow-inner flex space-x-2 items-center block bg-white p-2 rounded-xl border border-gray-500">
                <i class="fas fa-calendar text-gray-500"></i>
                <select name="month" class="bg-transparent focus:outline-none">
                    <option class="text-red-500 font-semibold" value="{{ $currentDate->format('n') }}">
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

                <div title="Year"
                    class="shadow-inner flex space-x-2 items-center p-2 bg-white rounded-xl border border-gray-500">
                    <i class="fa-solid fa-calendar-days text-gray-500"></i>
                    <select name="year" class="bg-transparent focus:outline-none">
                        <option class="text-red-500 font-semibold" value="{{ $currentDate->format('Y') }}">
                            {{ $currentDate->format('Y') }}
                        </option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>
            </div>

            <div title="Category"
                class="shadow-inner flex items-center p-2 bg-white space-x-2 rounded-xl border border-gray-500">
                <i class="fa-solid fa-list text-gray-500"></i>

                <select name="category" class="bg-transparent focus:outline-none">

                    <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                        {{ $currentCategory->title }}
                    </option>
                    @hasrole('admin')
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>

                    @endforeach
                    @endhasrole

                    @hasrole('staff')
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>

                    @endforeach
                    @endhasrole

                </select>
            </div>


        </form>
    </div>
</div>

<div id="modal-item"></div>
<div id="calendar" class="w-full h-full flex justify-center items-center overflow-hidden">
    <!-- Calendar Grid -->
    <div id="calendar-grid" class="grid grid-cols-7 gap-2 p-6 shadow-lg w-full h-full">

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
                    class="{{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-105' : '' }} relative cursor-auto calendar-cell {{ $hasRecord == true ? 'bg-blue-500 text-white cursor-pointer shadow-md' : 'bg-gray-300' }} p-4 flex flex-col items-center justify-center font-semibold overflow-hidden group">
                    <div class="flex justify-center items-center">
                        <h1 class="drop-shadow font-bold text-4xl {{ $isSunday ? 'text-red-500' : '' }}">{{ $day }}</h1>
                    </div>
                    @if(!$hasRecord)
                        <div onclick="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @if($setting->transition == true)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    document.getElementById('transaction-form-{{$day}}').classList.add('animation-open');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    document.getElementById('transaction-add-{{$day}}').classList.remove('hidden');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @else
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    document.getElementById('transaction-add-{{$day}}').classList.remove('hidden');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @endif
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        "
                            title="Add Transaction"
                            class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white opacity-0 bg-gray-500 {{ $setting->transitiona == true ? '' : 'group-hover:opacity-100 transition-opacity duration-300 ease-in-out'}}">
                            <h1 class="flex justify-center items-center text-4xl">+</h1>
                        </div>

                    @endif
                </button>
                @include('admin.modals.transaction-add')
        @endfor
    </div>
</div>

<script>
    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>