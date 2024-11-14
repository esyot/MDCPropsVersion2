<div class="w-full p-4">


    <header class="flex justify-between py-4 w-full bg-blue-500 shadow-md items-center px-2 rounded-t-lg">
        <div class="flex space-x-3">
            <!-- <button>
            <i class="fas fa-chevron-circle-left fa-xl text-white hover:opacity-50 shadow-md"></i>
        </button>
        <button>
            <i class="fas fa-chevron-circle-right fa-xl text-white hover:opacity-50 shadow-md"></i>
        </button> -->
        </div>

        <span class="text-white text-2xl text-center font-bold"> {{$selectedMonth}}</span>

        <button title="Expand" onclick="calendarExpand()" class="px-4">
            <i class="fas fa-maximize fa-xl text-white hover:opacity-50"></i>
        </button>

    </header>
    <div id="" class="grid grid-cols-7 bg-white shadow-md w-full h-full">

        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="font-bold p-4 bg-gray-100 text-2xl text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
                {{ $day }}
            </div>
        @endforeach

        <!-- Calculate the starting position of the first day -->
        @php
            $firstDayOfMonth = $currentDate->copy()->startOfMonth();
            $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
        @endphp

        <!-- Add empty cells for days before the first of the month -->
        @for ($i = 0; $i < $startDayOfWeek; $i++)
            <div class="calendar-cell p-4"></div>
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
                    class="{{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-90' : '' }} relative cursor-pointer {{ $hasRecord == true ? 'bg-blue-500 text-white ' : '' }} p-4 flex flex-col items-center justify-center font-semibold overflow-hidden group">
                    <div class="flex justify-center items-center">
                        <h1 class="drop-shadow text-4xl font-normal {{ $isSunday ? 'text-red-500' : '' }}">{{ $day }}</h1>
                    </div>
                    @if(!$hasRecord)
                        <div onclick="toggleTransactionForm({{$day}}, {{$setting->transition}})" title="Add Transaction"
                            class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white opacity-0 bg-gray-400 {{ $setting->transitiona == true ? '' : 'group-hover:opacity-100 transition-opacity duration-300 ease-in-out'}}">
                            <h1 class="flex justify-center items-center text-4xl">+</h1>
                        </div>

                    @endif
                </button>
                @include('admin.modals.transaction-add')
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