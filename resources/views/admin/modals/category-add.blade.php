<form id="category-add-form" method="POST" action="{{ route('category-add') }}" enctype="multipart/form-data">
    @csrf
    <div id="category-add-modal"
        class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div id="category-add-content" class="bg-white shadow-md max-w-full rounded">

            <div class="flex justify-between p-2 items-center space-x-6">
                <h1 class="px-2 text-xl font-bold">Add Category</h1>
                <button type="button" onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                    class="text-xl hover:text-gray-400 font-medium">&times;</button>
            </div>

            <div class="bg-gray-100 p-2 mt-2 border-t border-b border-gray-300">
                <div id="image-preview" class="flex justify-center mt-2 space-x-2"></div>

                <div class="mt-2 flex flex-col">
                    <input type="file" name="files[]" class="file-upload-input" accept="image/*" multiple required
                        onchange="previewImages(event)">
                    <small class="font-bold">Note:
                        <i class="text-xs font-normal">Make sure the selected files are in "jpg", "png", or "jpeg"
                            format.</i>
                    </small>
                </div>



                <div class="mt-2">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" title="Title"
                        class="block p-2 border border-gray-300 rounded w-full" required>
                </div>

                <!-- <div class="flex flex-col mt-2">
                    <label for="approval">Managed By:</label>
                    <select name="managed_by" class="block p-2 border border-gray-300 rounded" required>
                        <option value="admin"
                            title="Only the administrator can approve items that belong to this category.">Admin only
                        </option>
                        <option value="staff" title="Only the staff can approve items that belong to this category.">
                            Staff
                            only</option>
                        <option value="both" title="Both roles can approve items within this category.">Both</option>
                    </select>
                </div> -->
            </div>

            <div class="flex justify-end p-2 space-x-1">

                <button type="button" onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                    class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 shadow-md rounded">
                    Close
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
                    Add
                </button>
            </div>

        </div>
    </div>
</form>