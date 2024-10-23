@foreach ($items as $item)

    <div id="card" title="Click to preview"
        onclick="document.getElementById('item-{{$item->id}}').classList.remove('hidden')"
        class="flex flex-col text-white relative bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transition-transform  duration-300 hover:scale-90' : '' }}">
        <!-- Image Container -->
        <div class="flex justify-center items-center bg-gray-200 h-3/4 shadow-inner">
            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                alt="{{ $item->name }}" class="w-full h-full object-cover">
        </div>
        <!-- Name Container -->
        <div class="bg-blue-500 w-full h-1/4 shadow-md text-center flex flex-col items-center justify-center">
            <h1 class="text-lg font-semibold truncate">{{ $item->name }}</h1>
        </div>
    </div>
    @include('admin.modals.item-preview')
@endforeach

@if(count($items) == 0)

    <div class="flex fixed inset-0 justify-center items-center">

        <h1>No results found</h1>

    </div>
@endif