@extends('admin.layouts.header')
@section('content')

<div id="dashboard" class="h-full= w-full">

    @if ($categoriesIsNull == false)


        @if (session()->has('tracking_code'))
            <div id="QR" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
                <div class="bg-white p-4 rounded mx-2">
                    <div class="flex justify-center">
                        <h1 class="text-2xl text-center font-bold">Reservation has been successfully submitted!</h1>
                    </div>

                    <div class="flex mt-2 justify-center">
                        <canvas id="canvas" class=""></canvas>
                    </div>

                    <div class="flex space-x-2 p-2 justify-center">
                        <h1>Tracking Code: </h1>
                        <span class="font-bold">
                            {{ session('tracking_code') }}
                        </span>
                    </div>

                    <div class="flex justify-center">
                        <small class="font-bold">Note: <i class="font-normal text-xs">Please save this qr code.</i></small>
                    </div>

                    <div class="flex justify-center mt-2 space-x-2">

                        <button onclick="document.getElementById('QR').remove()"
                            class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Done</button>
                        <button onclick="printQR()" class="px-4 py-2 bg-green-500 text-green-100 hover:opacity-50 rounded">Print
                            QR Code</button>
                    </div>
                </div>
            </div>

            <script>

                const trackingCode = @json(session('tracking_code'));

                var qr = new QRious({
                    element: document.getElementById('canvas'),
                    value: trackingCode,
                    size: 300
                });

                var canvas = document.getElementById('canvas');
                var dataURL = canvas.toDataURL('image/png');
                var img = new Image();
                img.src = dataURL;

                function printQR() {


                    var printWindow = window.open('', '', 'width=600,height=600');
                    printWindow.document.write('<html><head><title>Print QR Code</title></head><body style="margin: 0; padding: 0; position: relative; width: 100%; height: 100%; overflow: hidden;">');


                    printWindow.document.write('<img src="' + dataURL + '" style=" width: 35%; height: auto;" />');
                    printWindow.document.write('<p style="">Tracking Code: ' + '{{ session("tracking_code") }}' + '</p>');

                    printWindow.document.write('</body></html>');

                    printWindow.document.close();


                    printWindow.print();

                }

            </script>
        @endif

        @include('admin.partials.calendar')
    @else

        @include('admin.partials.errors.category-null-error')

    @endif

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
@endsection