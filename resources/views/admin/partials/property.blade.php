@foreach ($properties as $property)
    <li onclick="selectProperty('{{$day}}', '{{$property->id}}', '{{$property->name}}', '{{$property->qty}}');"
        class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">
        {{ $property->name }}
    </li>
@endforeach

@if(count($properties) == 0)
    <li class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">No match found!</li>
@endif

<script>

    function selectProperty(day, property_id, property_name, maxQty) {
        document.getElementById('property-id-' + day).value = property_id;
        document.getElementById('property-' + day).value = property_name;
        document.getElementById('propertiesListModal-' + day).classList.toggle('hidden');
    }
</script>