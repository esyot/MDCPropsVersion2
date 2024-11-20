@foreach ($properties as $property)
    <li onclick="selectProperty('{{$day}}', '{{$property->id}}', '{{$property->name}}', '{{$property->qty}}');"
        class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">
        {{ $property->name }}
    </li>
@endforeach

<input id="field-no-{{$day}}" type="hidden">

<input type="hidden" id="new-selected-property-id-{{$day}}">

@if(count($properties) == 0)
    <li class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">No match found!</li>
@endif

<script>

    function selectProperty(day, property_id, property_name, maxQty) {
        const count = document.getElementById('field-no-' + day).value;
        const allSelectedProperties = document.getElementById('all-selected-properties-on-' + day);

        const allSelectedPropertiesArray = [];

        allSelectedPropertiesArray.push(property_id);

        // console.log(allSelectedPropertiesArray);
        document.getElementById('property-name-' + day + '-' + count).value = property_name;
        document.getElementById('property-qty-' + day + '-' + count).min = 1;
        document.getElementById('property-qty-' + day + '-' + count).value = 1;
        document.getElementById('property-qty-' + day + '-' + count).max = maxQty;
        document.getElementById('property-qty-' + day + '-' + count).placeholder = 'max:' + maxQty;
        document.getElementById('property-id-' + day + '-' + count).value = property_id;

        document.getElementById('new-selected-property-id-' + day).value = allSelectedPropertiesArray;

    }
</script>