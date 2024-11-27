<div class="p-2 w-full">
    <div class="shadow-md">

        <header class="flex justify-between py-4 w-full bg-blue-500  items-center px-4 rounded-t-lg">
            <span class="text-white text-2xl text-center font-bold"> {{$selectedMonth}}</span>

            <button title="Expand" onclick="calendarExpand()">
                <i class="fas fa-maximize fa-lg text-white hover:opacity-50"></i>
            </button>
        </header>

        <div class="grid grid-cols-7 bg-white p-2">

            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class=" font-bold bg-gray-100 text-3xl text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
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

                            // Check if the current day is a Sunday
                            $isSunday = \Carbon\Carbon::parse($currentDay)->dayOfWeek === 0;

                            // Check if the current day is today
                            $isToday = \Carbon\Carbon::parse($currentDay)->isToday();

                            // Filter the reservations to get those that match the current day or fall within a reservation range
                            $reservedProperties = $reservationsCollection->filter(function ($property) use ($currentDay) {
                                return isset($property->date_start) && isset($property->date_end) &&
                                    \Carbon\Carbon::parse($property->date_start)->lte($currentDay) &&
                                    \Carbon\Carbon::parse($property->date_end)->gte($currentDay);
                            });


                        @endphp



                        <button @if($hasRecord)
                            hx-get="{{ $hasRecord ? route('admin.calendar-day-view', ['date' => \Carbon\Carbon::parse($currentDate->format('Y-m') . '-' . $day)->format('Y-m-d'), 'category_id' => $currentCategory->id]) : '#' }}"
                            hx-target="#calendar-day-view-{{$day}}" hx-swap="innerHTML" hx-trigger="click"
                        title="Click to preview reserve properties" @endif
                            class="{{ $hasRecord ? 'border bg-gray-100' : '' }} relative justify-between h-full hover:opacity-50 cursor-pointer flex flex-col items-center justify-center font-semibold overflow-hidden group">
                            <div class="w-full flex flex-col  relative">
                                @foreach ($reservedProperties as $index => $property)
                                                @php

                                                    $dateStart = \Carbon\Carbon::parse($property->date_start)->toIso8601String();
                                                @endphp

                                                <div class="w-full py-[3px]" data-date-start="{{ $dateStart }}" data-index="{{ $index }}">

                                                </div>
                                @endforeach

                                <script>
                                    function generateColorFromPalette(index) {
                                        const colors = [
                                            { hue: 60, name: 'yellow' },
                                            { hue: 200, name: 'blue' },
                                            { hue: 120, name: 'green' },
                                            { hue: 30, name: 'orange' },
                                            { hue: 0, name: 'red' },
                                        ];

                                        const color = colors[index % colors.length];

                                        const saturation = 60 + (index % 20);
                                        const lightness = 50 + (index % 30);


                                        return `hsl(${color.hue}, ${saturation}%, ${lightness}%)`;
                                    }


                                    document.querySelectorAll('[data-date-start]').forEach(function (element) {

                                        const index = parseInt(element.getAttribute('data-index'), 10);

                                        const color = generateColorFromPalette(index);

                                        element.style.backgroundColor = color;
                                    });
                                </script>



                            </div>

                            <h1 class="p-4 drop-shadow text-4xl font-normal {{ $isSunday ? 'text-red-500' : '' }}">
                                {{ $day }}
                            </h1>

                            @if($isToday)
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

    let propertyCount = 1;

    function insertProperty(day) {
        // Increment the property count for the specific day
        propertyCount++;

        // Create the new property element
        const newProperty = document.createElement('div');
        newProperty.id = 'property-' + propertyCount; // Unique property ID

        // Set the inner HTML with unique ids for each property
        newProperty.innerHTML = `
    <div id="property-selected-on-${day}-${propertyCount}" class="flex items-center space-x-4">
        <div class="flex-1">
            <div id="property-container-1" onclick="document.getElementById('propertiesListModal-${day}').classList.remove('hidden'); document.getElementById('field-no-${day}').value = ${propertyCount};"
                class="flex items-center justify-between w-full p-2 border border-gray-300 cursor-pointer rounded">
                <input type="text" title="Items" id="property-name-${day}-${propertyCount}" class="focus:outline-none cursor-pointer w-full" placeholder="Select a property" readonly required>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        <div class="flex-1">
            <div class="flex items-center space-x-2">
                <input  type="number" placeholder="0" name="property-qty-${propertyCount}" id="property-qty-${day}-${propertyCount}" class="block p-2 border border-gray-300 rounded w-full">
                <button title="Remove this property field" type="button" onclick="removePropertyField('${day}', '${propertyCount}')" class="hover:opacity-50">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                </button>
            </div>
        </div>
        <input type="hidden" id="property-id-${day}-${propertyCount}">
    </div>
`;

        let script = document.createElement('script');
        script.textContent = `
    document.getElementById('property-qty-${day}-${propertyCount}').addEventListener('input', function () {
        let value = parseInt(document.getElementById('property-qty-${day}-${propertyCount}').value, 10);
        if (value < document.getElementById('property-qty-${day}-${propertyCount}').min) {
            document.getElementById('property-qty-${day}-${propertyCount}').value = document.getElementById('property-qty-${day}-${propertyCount}').min;
        }
        if (value > document.getElementById('property-qty-${day}-${propertyCount}').max) {
            document.getElementById('property-qty-${day}-${propertyCount}').value = document.getElementById('property-qty-${day}-${propertyCount}').max;
        }
    });
`;


        newProperty.appendChild(script);



        document.getElementById('properties-' + day).appendChild(newProperty);
    }

    function removePropertyField(day, count) {

        const allValues = document.getElementById('all-selected-properties-on-' + day).value;
        const propertyId = document.getElementById('property-id-' + day + '-' + count).value;

        document.getElementById('property-selected-on-' + day + '-' + count).remove();

        const textArray = allValues.split('');

        textArray.splice(1, count - 1);

        const newText = textArray.join('');

        document.getElementById('all-selected-properties-on-' + day).value = newText;
    }


</script>