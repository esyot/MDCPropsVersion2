<div id="delete-category-{{$category->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">
    <div class="bg-white rounded shadow-md">
        <div class="bg-red-500 py-1 rounded-t">

        </div>
        <header class="flex px-4 py-2 items-center space-x-2 border-b">
            <i class="fas fa-trash text-2xl bg-red-500 p-2 px-3.5 rounded-full text-red-100"></i>
            <section>
                <h1 class="text-xl font-medium">Delete Confirmation</h1>
                <h1>Are you sure to delete this category?</h1>
                <small class="font-bold">
                    Note:
                    <i class="font-normal">Once deleted, all properties that belongs to this
                        category will be
                        also
                        deleted.</i>
                </small>
            </section>

        </header>
        <footer class="bg-gray-100 rounded-b flex justify-end space-x-1 p-2">
            <button type="button"
                onclick="document.getElementById('delete-category-{{$category->id}}').classList.toggle('hidden')"
                class="px-4 py-2 border border-gray-300 text-gray-800 hover:opacity-50 rounded">
                No
            </button>
            <a href="{{ route('admin.category-delete', ['id' => $category->id]) }}"
                class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">
                Yes
            </a>
        </footer>
    </div>
</div>
<form action="{{ route('admin.category-update', ['category_id' => $category->id]) }}" method="POST">
    @csrf
    <div id="category-update-{{$category->id}}"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40 hidden">
        <div class="bg-white w-[300px] rounded shadow-md">

            <div class="flex p-2 items-center justify-between">
                <h1 class="text-xl font-medium">Edit Details</h1>
                <button type="button"
                    onclick="document.getElementById('delete-category-{{$category->id}}').classList.toggle('hidden')"
                    title="Delete this category with all properties assigned to it." class="hover:opacity-50">
                    <i class="fas fa-trash text-red-500"></i>
                </button>

            </div>

            <div class="bg-gray-100 p-2 border-t border-b border-gray-300">

                <div>
                    <label for="">Title:</label>
                    <input type="text" name="title" class="w-full p-2 border border-gray-300 shadow-inner rounded"
                        value="{{$category->title}}">
                </div>


            </div>

            <div class="flex justify-end space-x-2 p-2">


                <button type="button"
                    onclick="document.getElementById('category-update-{{$category->id}}').classList.add('hidden')"
                    class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 shadow-md rounded">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
                    Update
                </button>
            </div>



        </div>
    </div>

</form>