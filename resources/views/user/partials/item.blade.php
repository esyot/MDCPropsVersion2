@foreach($items as $item)
    <div onclick="openCalendar({{ $item->id }})"
        class="flex flex-col justify-between h-full w-1/3 sm:w-1/4 md:w-1/5 lg:w-1/6 px-2 mt-4 transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50 cursor-pointer">
        <div class="shadow-lg rounded-lg overflow-hidden relative">
            <div class="w-full h-0 pt-[50%] relative">
                <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                    alt="Image" class="absolute top-0 left-0 w-full h-full object-contain z-0">
            </div>
            <div class="bg-blue-500 text-blue-100 p-2 flex flex-col justify-center text-center relative z-10"
                style="height: 60px;">
                <h2 class="font-semibold text-[calc(1.5rem + 1vw)] leading-[1] max-w-full break-words">
                    {{ $item->name }}
                </h2>
            </div>
        </div>
    </div>

    @include('user.modals.preview-date')
@endforeach