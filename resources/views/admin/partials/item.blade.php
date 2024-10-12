@foreach ($items as $item)
    <li onclick="
                document.getElementById('item-id-{{$day}}').value = '{{$item->id}}';
                document.getElementById('item-{{$day}}').value = '{{$item->name}}';
                document.getElementById('itemListModal-{{$day}}').classList.add('hidden');
                        " class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">
        {{ $item->name }}
    </li>

@endforeach
@if(count($items) == 0)
    <li class="flex justify-center p-2 hover:text-white hover:bg-gray-500 cursor-pointer">No match found!</li>
@endif