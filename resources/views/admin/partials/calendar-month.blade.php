<div class="p-2 w-full">
    <div class="shadow-md">

        <header class="flex justify-between py-4 w-full bg-blue-500  items-center px-4 rounded-t-lg">
            <span class="text-white text-2xl text-center font-bold"> {{$selectedMonth}}</span>

            <button title="Expand" onclick="calendarExpand()">
                <i class="fas fa-maximize fa-lg text-white hover:opacity-50"></i>
            </button>
        </header>

        <div class="grid grid-cols-7 bg-white">

            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class=" font-bold p-4 bg-gray-100 text-3xl text-center {{ $day == 'Sun' ? 'text-red-500' : '' }}">
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