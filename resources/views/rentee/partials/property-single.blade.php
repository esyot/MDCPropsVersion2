<style>
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
            margin-inline: 6px;

        }
    }
</style>

@if(count($properties) > 0)
    @foreach($properties as $property)
        <div onclick="openCalendar({{ $property->id }})"
            class="flex flex-col cursor-pointer justify-between h-full transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50 cursor-pointer">
            <div class="shadow-lg rounded-lg overflow-hidden relative">
                <div class="w-full h-0 pt-[50%] relative">
                    <img src="{{ asset('storage/images/categories/' . $property->category->folder_name . '/' . $property->img) }}"
                        alt="Image" class="absolute top-0 left-0 w-full h-full object-contain z-0">
                </div>
                @if ($property->price == 0.00)

                    <div class="flex justify-center text-red-500">
                        For Borrowing only
                    </div>
                @else
                    <div class="flex justify-center text-red-500">
                        For Borrowing & Renting
                    </div>
                @endif
                <div id="item-description" class="p-2 text-blue-100 text-xs bg-blue-500">
                    <div id="name">
                        <h1 class="font-bold">Name:</h1>
                        <span class="text-yellow-300">{{$property->name}}</span>
                    </div>
                    @if ($property->price != 0.00)


                        <div id="price">
                            <h1 class="font-bold">Price:</h1>
                            <div class="text-yellow-300 flex space-x-1">
                                <span>₱{{$property->price}}</span>

                                <h1>per</h1>
                                <span>
                                    {{ $property->per }}
                                </span>
                            </div>

                        </div>
                    @endif
                    <div id="qty">
                        <h1 class="font-bold">Quantity:</h1>
                        <span class="text-yellow-300">{{$property->qty}} pc/s</span>
                    </div>
                </div>
            </div>
        </div>

        @include('rentee.modals.preview-date')
    @endforeach
@else
    <div class="flex justify-center col-span-full">
        <h1>
            No items found
        </h1>
    </div>
@endif