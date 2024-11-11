<style>
    @media(orientation:portrait) {
        #item-description {
            display: flex;
            justify-content: center;
            height: 100%;
            align-items: center;

        }

        #item-description #name,
        #price,
        #qty {
            display: flex;
            margin-inline: 3px;
        }

    }

    @media(orientation:landscape) {
        #item-description {
            display: flex;
            justify-content: center;

        }

        #item-description #name,
        #price,
        #qty {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-inline: 2px;
            margin: 2px;
        }

    }
</style>
@foreach ($items as $item)

    <div id="card" title="Click to preview"
        onclick="document.getElementById('item-{{$item->id}}').classList.remove('hidden')"
        class="flex flex-col text-white relative bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transition-transform  duration-300 hover:scale-90' : '' }}">
        <!-- Image Container -->
        <div class="flex justify-center items-center bg-gray-200 h-3/4 shadow-inner">
            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                alt="{{ $item->name }}" class="w-full h-full object-cover">
        </div>
        @if ($item->price == 0.00)
            <div class="border border-red-500 bg-white text-center text-black">
                <small class="text-red-500">Available for Borrowing only</small>
            </div>
        @else
            <div class="border border-red-500 bg-white text-center text-black">
                <small class="text-red-500">Available for Borrowing & Renting</small>
            </div>
        @endif
        <div id="item-description" class="p-2 text-blue-100 text-xs bg-blue-500">
            <div id="name" class="flex space-x-1">
                <h1 class="font-bold">Name</h1>
                <span class="text-yellow-300 truncate">{{$item->name}}</span>
            </div>
            @if ($item->price != 0.00)
                <div id="price" class="space-x-1">
                    <h1 class="font-bold">Price</h1>
                    <div class="truncate text-yellow-300 flex space-x-1">
                        <span>₱{{$item->price}}</span>

                        <h1>by</h1>
                        <span>
                            {{ $item->by }}
                        </span>
                    </div>

                </div>
            @endif
            <div id="qty" class="space-x-1">
                <h1 class="font-bold">Quantity</h1>
                <span class="truncate text-yellow-300">{{$item->qty}} pc/s</span>
            </div>
        </div>


    </div>
    @include('admin.modals.item-preview')
@endforeach

@if(count($items) == 0)

    <div class="relative fixed inset-0">
        <h1>No results found</h1>
    </div>
@endif