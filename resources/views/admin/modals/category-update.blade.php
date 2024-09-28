<form action="{{ route('categoryUpdate', ['category_id' => $category->id]) }}" method="POST">
    @csrf
    <div id="category-update-{{$category->id}}"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white w-[200px] rounded shadow-md">

            <div class="flex p-2 items-center justify-between">
                <h1 class="text-xl font-medium">Edit Details</h1>
                <button type="button" title="Close"
                    onclick="document.getElementById('category-update-{{$category->id}}').classList.add('hidden')"
                    class="text-xl font-bold hover:text-gray-500">&times;</button>

            </div>

            <div class="bg-gray-100 p-2 border-t border-b border-gray-300">

                <div>
                    <label for="">Title:</label>
                    <input type="text" name="title" class="w-full p-2 border border-gray-300 shadow-inner rounded"
                        value="{{$category->title}}">
                </div>

                <div>
                    <label for="">Approval level:</label>
                    <select name="approval_level" class="w-full p-2 border border-gray-300 shadow-inner rounded"
                        value="{{$category->title}}">
                        <option value="{{ $category->approval_level }}">
                            @if($category->approval_level == 3)
                                Both
                            @elseif($category->approval_level == 2)
                                Staff Only

                            @elseif($category->approval_level == 1)
                                Admin Only
                            @endif
                        </option>
                        <option value="1">Admin Only</option>
                        <option value="2">Staff Only</option>
                        <option value="3">Both</option>
                    </select>


                </div>
            </div>

            <div class="flex justify-center space-x-2 mt-2 pb-2">
                <button type="submit"
                    class="px-4 py-2 bg-green-100 hover:bg-green-500 hover:text-green-100 text-green-800 shadow-md rounded ">
                    Update
                </button>

                <button type="button"
                    onclick="document.getElementById('category-update-{{$category->id}}').classList.add('hidden')"
                    class="px-4 py-2 bg-red-100 hover:bg-red-500 hover:text-red-100 text-red-800 shadow-md rounded ">
                    Cancel
                </button>
            </div>



        </div>
    </div>

</form>