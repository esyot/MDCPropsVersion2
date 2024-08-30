@extends('layouts.header')
@section('content')

<div class="flex p-4 bg-gray-300 text-white">
    <div class="bg-white p-2 rounded-xl border border-gray-400">

        <form action="{{ route('itemsFilter') }}">

            @csrf

            <i class="text-black fa-solid fa-list"></i>
            <select name="category" class="text-black bg-transparent focus:outline-none" onchange="this.form.submit();">

                <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                    {{ $currentCategory->title }}
                </option>

                @foreach($categories as $category)
                    <option class="text-black" value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>

        </form>
    </div>

</div>

<div id="item-add-modal" class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div id="item-add-content" class="bg-white shadow-md max-w-full rounded">

        <div class="flex justify-between px-1 items-end space-x-6">
            <h1 class="px-2 text-xl font-bold">Add Item</h1>
            <button onclick="document.getElementById('item-add-modal').classList.add('hidden')"
                class="text-4xl hover:text-gray-400 font-medium">&times;</button>

        </div>
        <form action="{{ route('itemAdd') }}" method="POST">
            @csrf
            <div class="px-4">

                <div class="py-2 flex flex-col">
                    <label for="img">Image:</label>
                    <input type="file" name="img" accept="image/*" required>
                </div>
                <div class="mt-2">
                    <label for="title">Item name:</label>
                    <input type="text" name="name" placeholder="Name" title="Title"
                        class="block p-2 border border-gray-300 rounded w-full" required>
                </div>

                <div class="mt-2">
                    <label for="qty">Quantity:</label>
                    <input type="number" name="qty" placeholder="Quantity" title="Title"
                        class="block p-2 border border-gray-300 rounded w-full" required>
                </div>

                <div class="flex flex-col mt-2">
                    <label for="category">Category:</label>
                    <select name="category" title="Category" class="block p-2 border border-gray-300 rounded" required>
                        @foreach($categories as $category)
                            <option class="text-black" value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end my-2 space-x-1">
                    <button class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">Add</button>
                    <button onclick="document.getElementById('item-add-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-500 text-gray-100 hover:bg-gray-800 rounded">Close</button>
                </div>
        </form>
    </div>
</div>
</div>

<div class="relative m-4 overflow-y-auto">
    <div class="flex flex-wrap gap-6">
        <!-- Add Category Button -->
        <div title="Add a new category"
            class="flex flex-col bg-gray-200 rounded-lg  hover:bg-gray-300  hover:shadow-inner w-64 h-64 overflow-hidden">
            <div class="flex items-center justify-center cursor-pointer hover:text-gray-800 text-gray-400 "
                onclick="document.getElementById('item-add-modal').classList.remove('hidden')">
                <h1 class="text-8xl mb-3 font-bold py-2 w-50 h-50 object-cover cursor-pointer">+</h1>
            </div>
            <div class="bg-blue-500 w-full h-full shadow-md text-center p-2 flex items-center justify-center">
                <h1 class="text-white py-2 font-bold">Add Item</h1>
            </div>
        </div>





        <!-- Category Items -->
        @foreach ($items as $item)
            <div class="flex flex-col text-white rounded-lg w-64 h-64 overflow-hidden">
                <div class="flex justify-center items-center bg-gray-200">
                    <img src="{{ asset('images/categories/' . $currentCategory->folder_name . '/' . $item->img) }}"
                        alt="{{ $item->name }}" class="p-2 w-50 h-50 object-cover">
                </div>
                <div class="bg-blue-500 w-full h-full shadow-md text-center p-2 flex items-center justify-center">
                    <h1 class="text-lg font-semibold">{{ $item->name }}</h1>
                </div>
            </div>
        @endforeach



    </div>
</div>


@endsection