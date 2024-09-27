@extends('admin.layouts.header')
@section('content')
@if ($categoriesIsNull == false)

    <div id="navbar" class="flex p-4 text-white">
        <div class="bg-white p-2 rounded-xl border border-gray-400">
            <form action="{{ route('itemsFilter') }}">
                @csrf
                <i class="text-black fa-solid fa-list"></i>
                <select name="category" class="text-black bg-transparent focus:outline-none" onchange="this.form.submit();">

                    <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                        {{ $currentCategory->title }}
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>

                    @endforeach

                </select>
            </form>
        </div>
    </div>

    <div id="item-add-modal" class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div id="item-add-content" class="bg-white shadow-md max-w-full rounded ">
            <div class="flex justify-between p-1 items-center space-x-6 border-b border-gray-300">
                <h1 class="p-2 text-xl font-bold">Add Item</h1>
                <button onclick="document.getElementById('item-add-modal').classList.add('hidden')"
                    class="text-4xl hover:text-gray-400 font-medium">&times;</button>
            </div>
            <form action="{{ route('itemAdd') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-4 bg-gray-100 py-2">
                    <div class="flex flex-col">

                        <label for="img" class="font-medium">Image:</label>

                        <input type="file" name="img" accept="image/*" id="img"
                            class="block p-2 border border-gray-300 bg-white rounded" required>

                        <img id="img-preview" src="" alt="Image Preview" class="mt-2 h-[150px] object-cover hidden">

                        <small class="font-bold">Note:
                            <i class="font-normal">Make sure the selected file is in "jpg", "png", or "jpeg" format.</i>
                        </small>
                    </div>

                    <div class="mt-2">
                        <label for="title" class="font-medium">Item name:</label>
                        <input type="text" name="name" placeholder="Name" title="Title"
                            class="block p-2 border border-gray-300 rounded w-full" required>
                    </div>

                    <div class="mt-2">
                        <label for="qty" class="font-medium">Quantity:</label>
                        <input type="number" name="qty" placeholder="Quantity" title="Title"
                            class="block p-2 border border-gray-300 rounded w-full" required>
                    </div>

                    <div class="mt-2">
                        <label for="category" class="font-medium">Category:</label>
                        <select name="category" title="Category" class="block p-2 border border-gray-300 rounded" required>
                            @if(count($categories) > 0)
                                <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                                    {{ $currentCategory->title }}
                                </option>
                            @endif
                            @foreach($categories as $category)
                                <option class="text-black" value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>

                    </div>


                </div>

                <div class="flex justify-end border-t border-gray-300 shadow-t-lg">
                    <div class="flex p-2 space-x-1">
                        <button class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">Add</button>

                        <button onclick="document.getElementById('item-add-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-500 text-gray-100 hover:bg-gray-800 rounded">Close</button>

                    </div>

                </div>
            </form>
        </div>
    </div>

    <div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
        <div class="flex flex-wrap flex-grow gap-2">
            <!-- Add Category Button -->
            <div title="Add a new category"
                class="flex flex-col relative bg-gray-200 mr-2 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transition-transform transform duration-300 hover:scale-105' : '' }}">
                <div class="flex justify-center items-center bg-gray-200 h-3/4 cursor-pointer hover:text-gray-800 text-gray-400 "
                    onclick="document.getElementById('item-add-modal').classList.remove('hidden')">
                    <h1 class="text-8xl mb-3 font-bold py-2 w-50 h-50 object-cover cursor-pointer">+</h1>
                </div>
                <div class="bg-blue-500 w-full h-1/4 shadow-md text-center flex items-center justify-center">
                    <h1 class="text-lg font-semibold text-white truncate">Add Item</h1>
                </div>
            </div>

            <!--  Items -->
            @foreach ($items as $item)

                <div title="Click to preview" onclick="document.getElementById('item-{{$item->id}}').classList.remove('hidden')"
                    class="flex flex-col text-white relative bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transition-transform transform duration-300 hover:scale-105' : '' }}">
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

        </div>
    </div>



    <script>
        document.getElementById('img').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('img-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>


@else

    @include('admin.partials.errors.category-null-error')

@endif

@endsection