<div id="item-{{$item->id}}"
    class="flex fixed inset-0 justify-center items-center shadow-md bg-gray-800 bg-opacity-[50%] hidden">
    <div class="bg-white w-[20rem] space-y-2 rounded-sm">
        <div class="px-2 flex flex-wrap items-end justify-between">
            <h1 class="text-2xl font-medium">Item Preview</h1>
            <button class="text-4xl hover:text-gray-300"
                onclick="document.getElementById('item-{{$item->id}}').classList.add('hidden')">&times;</button>
        </div>

        <div class="px-2 pb-2">
            <div class="py-2">
                <label for="name">Name:</label>
                <input type="text" name="name" value="{{ $item->name }}"
                    class="block p-2 border border-gray-300 w-full rounded">
            </div>

            <div class="">
                <label for="name">Quantity:</label>
                <input type="text" name="qty" value="{{ $item->qty }}"
                    class="block p-2 border border-gray-300 w-full rounded">
            </div>

        </div>





    </div>

</div>