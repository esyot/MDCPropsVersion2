<div class="p-2 w-full">


    <header class="flex justify-between py-4 w-full bg-blue-500  items-center px-4 rounded-t-lg">
        <span class="text-white text-2xl text-center font-bold"> {{$selectedMonth}}</span>

        <button title="Expand" onclick="calendarExpand()">
            <i class="fas fa-maximize fa-xl text-white hover:opacity-50"></i>
        </button>
    </header>

    <div class="grid grid-cols-7">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="font-bold p-4 bg-gray-100 text-2xl text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
                {{ $day }}
            </div>
        @endforeach

        @php
            $firstDayOfMonth = $currentDate->copy()->startOfMonth();
            $startDayOfWeek = $firstDayOfMonth->dayOfWeek;
        @endphp

        @for ($i = 0; $i < $startDayOfWeek; $i++)
            <div class="p-4"></div>
        @endfor

        @for ($day = 1; $day <= $currentDate->daysInMonth; $day++)
                @php
                    $currentDay = $currentDate->copy()->day($day)->format('Y-m-d');
                    $hasRecord = in_array($currentDay, $daysWithRecords);
                    $reservedProperty = $reservations->firstWhere(function ($property) use ($currentDay) {
                        return \Carbon\Carbon::parse($property->date_start)->format('Y-m-d') === $currentDay;
                    });
                    $date = $reservedProperty ? \Carbon\Carbon::parse($reservedProperty->date_start)->format('Y-m-d') : null;
                    $isSunday = \Carbon\Carbon::parse($currentDay)->dayOfWeek === 0;
                    $isToday = \Carbon\Carbon::parse($currentDay)->isToday(); 
                @endphp

                <button @if($hasRecord)
                    hx-get="{{ $date ? route('admin.calendar-day-view', ['date' => $date, 'category_id' => $currentCategory->id]) : '#' }}"
                    hx-target="#calendar-day-view" hx-swap="innerHTML" hx-trigger="click"
                title="Click to preview reserve properties" @endif
                    class="relative hover:opacity-50 {{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-90' : '' }}  cursor-pointer {{ $hasRecord == true ? 'bg-gray-400 text-white ' : '' }} p-4 flex flex-col items-center justify-center font-semibold overflow-hidden group">

                    <div class="">
                        <h1 class="drop-shadow text-4xl font-normal {{ $isSunday ? 'text-red-500' : '' }}">
                            {{ $day }}
                        </h1>


                        @if($isToday)
                            <i class="fas fa-circle text-green-500 text-[8px] absolute bottom-1 z-50"></i>
                        @endif
                    </div>

                    @if(!$hasRecord)
                        <div title="Add a new reservation" onclick="toggleReservationForm({{$day}}, {{$setting->transition}})"
                            title="Add Transaction"
                            class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white opacity-0 bg-gray-400 {{ $setting->transition == true ? 'group-hover:opacity-100' : 'hover:opacity-100 transition-opacity duration-300 ease-in-out'}}">
                            <h1 class="flex justify-center items-center text-4xl">+</h1>
                        </div>
                    @endif
                </button>

                @include('admin.modals.reservation-add')
        @endfor

    </div>
</div>


<script>
    function calendarExpand() {
        document.getElementById('calendar-header').classList.toggle('hidden');
        document.getElementById('calendar-month').innerHTML = '';
        document.getElementById('calendar-month').classList.toggle('hidden');
        document.getElementById('calendar').classList.toggle('hidden');
    }
</script>