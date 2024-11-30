<!-- Modal for user delete confirmation -->
<div id="userDeleteConfirm-{{$user->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden ">
    <div class="bg-white shadow-md rounded w-[500px] mx-2">
        <div class="bg-red-500 py-1 rounded-t">

        </div>
        <div class="flex space-x-2  p-4 items-center border-b-2">
            <div class="bg-red-500 px-3 py-3.5 rounded-full">
                <i class="fa-solid fa-trash fa-2xl text-white"></i>
            </div>

            <div>
                <h1 class="text-2xl font-medium">Delete</h1>
                <span>Are you sure to delete this user?</span>
            </div>
        </div>
        <div class="flex justify-end space-x-1 p-2">

            <button type="button"
                onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.add('hidden')"
                class="font-medium px-4 py-2 border border-red-300 text-red-500 hover:opacity-50 rounded">No,
                cancel.
            </button>
            <a href="{{ route('admin.user-delete', ['id' => $user->id]) }}"
                class="font-medium px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">Yes,
                proceed.</a>
        </div>
    </div>
</div>