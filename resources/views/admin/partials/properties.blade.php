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

@foreach ($properties as $property)




    <div id="card" title="Click to preview"
        onclick="document.getElementById('property-preview-{{$property->id}}').classList.toggle('hidden');"
        class="flex flex-col text-white cursor-pointer relative bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transition-transform  duration-300 hover:scale-90' : '' }}">
        <!-- Image Container -->
        <div class="flex justify-center items-center bg-gray-200 h-3/4 shadow-inner">
            <img src="{{ asset('storage/images/categories/' . $property->category->folder_name . '/' . $property->img) }}"
                alt="{{ $property->name }}" class="w-full h-full object-cover">
        </div>

        @if ($property->price == 0.00)
            <div class="border border-red-500 bg-white text-center text-black">
                <small class="text-red-500">Available for Borrowing only</small>
            </div>
        @else
            <div class="border border-red-500 bg-white text-center text-black">
                <small class="text-red-500">Available for Borrowing & Renting</small>
            </div>
        @endif

        <div class="flex justify-center bg-blue-500 rounded-b-lg">

            <h1>{{$property->name}}</h1>

        </div>
    </div>

@endforeach

@if(count($properties) == 0)

    <div class="relative fixed inset-0">
        <h1>No match found</h1>
    </div>
@endif