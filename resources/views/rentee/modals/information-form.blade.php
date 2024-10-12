<div id="information-form" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-60 z-50 hidden">
    <div class="bg-white p-2 rounded w-80">
        <header>
            <h1 class="text-xl font-bold">Information</h1>
        </header>
        <section>
            <div>
                <label for="">Name:</label>
                <input type="text" placeholder="Input name" class="block p-2 border border-gray-300 w-full rounded">
            </div>

            <div>
                <label for="">Email:</label>
                <input type="email" placeholder="Input email" class="block p-2 border border-gray-300 w-full rounded">
            </div>
            <div>
                <label for="">Address:</label>
                <input type="text" placeholder="Input address" class="block p-2 border border-gray-300 w-full rounded">
            </div>
            <div>
                <label for="">Contact #:</label>
                <input type="text" placeholder="Input contact number"
                    class="block p-2 border border-gray-300 w-full rounded">
            </div>
        </section>
        <footer class="py-2 flex justify-end">
            <button onclick="document.getElementById('information-form').classList.add('hidden')"
                class="px-4 py-2 bg-blue-100 text-blue-800 hover:bg-blue-500 hover:text-blue-100 rounded">
                Submit

            </button>
        </footer>
    </div>
</div>