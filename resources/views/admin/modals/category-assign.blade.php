<div id="category-{{$category->id}}"
    class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <form action="{{ route('managedCategoriesUpdate', ['category_id' => $category->id]) }}" method="POST"
        class="bg-white rounded w-[300px]">
        @csrf
        <input id="isUncheckedAll-{{$category->id}}" type="hidden" name="isUncheckedAll" value="">


        <div class="flex items-center justify-between mb-2 m-2">
            <h1 class="text-xl font-medium">{{ $category->title }}</h1>
            <button type="button" aria-label="Close"
                onclick="document.getElementById('category-{{$category->id}}').classList.add('hidden')"
                class="text-2xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
        </div>

        <div class="flex flex-col bg-gray-100 p-2 border-b border-t border-gray-300">
            <h2 class="text-medium mb-2">Users can manage:</h2>
            @foreach ($users_for_roles as $user)
                <div class="flex items-center mb-1">
                    <input type="checkbox" class="category-checkbox" name="users[{{$category->id}}][]"
                        value="{{ $user->id }}" @if(isset($managedCategories[$user->id]) && in_array($category->id, $managedCategories[$user->id])) checked @endif>
                    <label class="ml-2">{{ $user->name }}</label>
                </div>
            @endforeach
        </div>
        <div class="flex p-2 justify-end space-x-1">

            <button type="button"
                class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 shadow-md rounded"
                onclick="document.getElementById('category-{{$category->id}}').classList.add('hidden')">Cancel
            </button>
            <button class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md  rounded"
                type="submit">Save</button>
        </div>

    </form>
</div>