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


                    <button onclick="document.getElementById('item-add-modal').classList.add('hidden')"
                        class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">Close</button>

                    <button class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">Add</button>

                </div>

            </div>
        </form>
    </div>
</div>