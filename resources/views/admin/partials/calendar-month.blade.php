<div class="p-2 w-full">
    <div class="shadow-md">

        <header
            class="flex justify-between py-2 w-full {{ $setting->darkMode ? 'bg-gray-500' : 'bg-blue-500' }} items-center px-4 rounded-t-lg">
            <span class="text-white text-2xl text-center font-bold"> {{$selectedMonth}}</span>

            <button title="Expand" onclick="calendarExpand()">
                <i class="fas fa-maximize fa-lg text-white hover:opacity-50"></i>
            </button>
        </header>

        <div class="grid grid-cols-7 bg-white">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="font-bold bg-gray-200 text-xl text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
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
                            // Ensure $reservations is a collection
                            $reservationsCollection = collect($reservations);

                            // Format the current day to match the date format 'Y-m-d'
                            $currentDay = $currentDate->copy()->day($day)->format('Y-m-d');

                            // Check if there are records for this day in the daysWithRecords array
                            $hasRecord = in_array($currentDay, $daysWithRecords);

                            // Find the first reserved property for the current day
                            $reservedProperty = $reservationsCollection->firstWhere(function ($property) use ($currentDay) {
                                return isset($property->date_start) && \Carbon\Carbon::parse($property->date_start)->format('Y-m-d') === $currentDay;
                            });

                            // Get the formatted date of the reserved property, if any
                            $date = $reservedProperty ? \Carbon\Carbon::parse($reservedProperty->date_start)->format('Y-m-d') : null;

                            // Filter the reservations to get those that match the current day or fall within a reservation range
                            $reservedProperties = $reservationsCollection->filter(function ($property) use ($currentDay) {
                                return isset($property->date_start) && isset($property->date_end) &&
                                    \Carbon\Carbon::parse($property->date_start)->lte($currentDay) &&
                                    \Carbon\Carbon::parse($property->date_end)->gte($currentDay);
                            });

                            // Sort the reservedProperties by the duration of the reservation (longest duration first)
                            $sortedReservedProperties = $reservedProperties->sortByDesc(function ($property) {
                                $start = \Carbon\Carbon::parse($property->date_start);
                                $end = \Carbon\Carbon::parse($property->date_end);
                                return $end->diffInDays($start); // Calculate the number of days between date_start and date_end
                            });
                        @endphp

                        <button @if($hasRecord)
                            hx-get="{{ $hasRecord ? route('admin.calendar-day-view', ['date' => \Carbon\Carbon::parse($currentDate->format('Y-m') . '-' . $day)->format('Y-m-d'), 'category_id' => $currentCategory->id]) : '#' }}"
                            hx-target="#calendar-day-view-{{$day}}" hx-swap="innerHTML" hx-trigger="click"
                        title="Click to preview reserve properties" @endif
                            class="{{ $hasRecord ? 'border border-white bg-blue-200' : '' }} relative justify-between h-full hover:opacity-50 cursor-pointer flex flex-col items-center justify-center font-semibold overflow-hidden group">

                            <div class="w-full flex flex-col relative">
                                @foreach ($sortedReservedProperties as $index => $property)
                                                @php
                                                    // Determine the reservation's status color
                                                    $statusColor = '';

                                                    if ($property->approvedByAdmin_at == null && $property->declinedByAdmin_at == null && $property->canceledByRentee_at == null) {
                                                        $statusColor = 'bg-yellow-500'; // Pending Admin Approval
                                                    } elseif ($property->approvedByAdmin_at != null && $property->approvedByCashier_at == null) {
                                                        $statusColor = 'bg-orange-500'; // Pending Payment
                                                    } elseif ($property->canceledByRentee_at != null) {
                                                        $statusColor = 'bg-red-500'; // Canceled
                                                    } elseif ($property->declinedByAdmin_at != null) {
                                                        $statusColor = 'bg-red-500'; // Declined By Admin
                                                    } elseif ($property->approvedByAdmin_at != null && $property->approvedByCashier_at != null && $property->claimed_at == null) {
                                                        $statusColor = 'bg-orange-500'; // Waiting to claim
                                                    } elseif ($property->approvedByAdmin_at != null && $property->approvedByCashier_at != null && $property->claimed_at != null && $property->returned_at == null) {
                                                        $statusColor = 'bg-orange-500'; // Waiting to return
                                                    } elseif ($property->approvedByAdmin_at != null && $property->approvedByCashier_at != null && $property->claimed_at != null && $property->returned_at != null) {
                                                        $statusColor = 'bg-green-500'; // Completed
                                                    }
                                                @endphp

                                                <div class="w-full border-t py-[2px] {{ $statusColor }}"
                                                    data-date-start="{{ \Carbon\Carbon::parse($property->date_start)->toIso8601String() }}"
                                                    data-index="{{ $index }}">
                                                    <!-- You can add any content here specific to the reservation, like property name, etc. -->
                                                </div>
                                @endforeach
                            </div>

                            <h1
                                class="p-4 drop-shadow text-4xl font-normal {{ \Carbon\Carbon::parse($currentDay)->dayOfWeek === 0 ? 'text-red-500' : '' }}">
                                {{ $day }}
                            </h1>

                            @if(\Carbon\Carbon::parse($currentDay)->isToday())
                                <i class="fas fa-circle text-green-500 text-[8px] absolute bottom-1"></i>
                            @endif

                            @if(!$hasRecord)
                                <div title="Add a new reservation" onclick="toggleReservationForm({{$day}}, {{$setting->transition}})"
                                    class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white opacity-0 bg-gray-400 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ $setting->transition == true ? 'group-hover:opacity-100' : 'hover:opacity-100 transition-opacity duration-300 ease-in-out'}}">
                                    <h1 class="flex justify-center items-center text-4xl">+</h1>
                                </div>
                            @endif
                        </button>

                        <div id="calendar-day-view-{{$day}}" class="absolute"></div>
                        @include('admin.modals.reservation-add')
            @endfor

        </div>
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