@foreach ($items as $item)
    <li onclick="selectItem('{{$day}}', '{{$item->id}}', '{{$item->name}}');"
        class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">
        {{ $item->name }}
    </li>
@endforeach
@if(count($items) == 0)
    <li class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">No match found!</li>
@endif



<script>

    function selectItem(day, itemId, itemName, maxQty) {

        document.getElementById('item-id-' + day).value = itemId;
        document.getElementById('item-' + day).value = itemName;
        document.getElementById('itemListModal-' + day).classList.add('hidden');
    }
</script>