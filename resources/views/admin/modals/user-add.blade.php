<div id="userAddModal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white w-[300px] shadow-md rounded">
        <form action="{{ route('userAdd') }}" method="POST">
            @csrf
            @method('POST')
            <div class="py-1 bg-blue-500 w-full rounded-t">

            </div>
            <div class="flex px-1 justify-between items-center">
                <h1 class="text-xl font-medium">Add new user</h1>
                <button type="button" onclick="document.getElementById('userAddModal').classList.add('hidden')"
                    class="text-2xl font-bold hover:text-gray-300 focus:outline-none">&times;</button>
            </div>
            <section class="px-2 space-y-2">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" placeholder="Input name"
                        class="block p-2 border border border-gray-300 w-full rounded" required>
                </div>

                <div>
                    <label for="name">Email:</label>
                    <input type="email" name="email" placeholder="Input email"
                        class="block p-2 border border border-gray-300 w-full rounded" required>
                </div>


            </section>
            <div class="mt-2 flex justify-end p-2 space-x-1 bg-gray-100 rounded-b">
                <button onclick="document.getElementById('userAddModal').classList.add('hidden')" type="button"
                    class="px-4 py-2 border border-gray-300 text-gray-800 hover:opacity-50 rounded">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">
                    Save
                </button>

            </div>
        </form>


    </div>
</div>