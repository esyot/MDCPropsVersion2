<!-- Modal Container -->

<form action="{{ route('itemUpdate', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div id="item-{{$item->id}}"
        class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-70 hidden">
        <!-- Modal Content -->
        <div class="bg-white w-[500px] mx-2 rounded-lg shadow-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-300">
                <h1 class="text-xl font-semibold text-gray-900">Item Preview</h1>
                <button type="button" class="text-4xl text-gray-600 hover:text-gray-800"
                    onclick="document.getElementById('item-{{$item->id}}').classList.add('hidden')">&times;</button>
            </div>



            <!-- Modal Body -->
            <div class="p-4 flex space-x-4 bg-gray-100">
                <!-- Image Section -->
                <div class="flex flex-col justify-center items-center space-y-5">

                    <img id="old-img-preview"
                        src="{{ asset('storage/images/categories/' . $currentCategory->folder_name . '/' . $item->img) }}"
                        alt="Item Image"
                        class="mt-2 w-full h-64 object-cover rounded-lg border border-gray-200 shadow-md ">

                    <img id="update-img-preview" src="" alt="Image Preview"
                        class="mt-2 w-full h-64 object-cover rounded-lg border border-gray-200 shadow-md hidden">

                    @hasrole('superadmin|admin')
                    <input type="file" name="update_img" accept="image/*" id="update_img"
                        class="block mt-2 w-full border border-gray-300 p-2 bg-white rounded">
                    <small class="font-bold">Note:
                        <i class="text-xs font-normal">Make sure the selected file is in "jpg", "png", or "jpeg"
                            format.</i>
                    </small>
                    @endhasrole

                </div>

                <!-- Details Section -->
                <div class="flex-1  space-y-4">
                    <div class="w-[200px]">
                        <label for="name" class="block text-gray-700 font-medium">Name:</label>
                        <input type="text" name="update_name" value="{{ $item->name }}"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>

                        <label for="qty" class="block text-gray-700 font-medium">Quantity:</label>
                        <input type="number" name="update_qty" value="{{ $item->qty }}"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>

                        <label for="qty" class="block text-gray-700 font-medium">Approval Level:</label>
                        <select name="update_approval_level" value="{{ $item->qty }}"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>
                            <option value="{{$item->approval_level}}">{{$item->approval_level}}</option>

                            <option value="admin">Admin Only</option>
                            <option value="staff">Staff Only</option>
                            <option value="both">Both</option>
                        </select>



                        <label for="update_category" class="font-medium">Category:</label>
                        <select name="update_category"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>
                            <option value="{{ $item->category->id }}">{{ $item->category->title }}</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>

                        <div class="flex items-center space-x-1">
                            <input id="isAvailableForRentingCheckbox-{{$item->id}}" name="isAvailableForRenting"
                                type="checkbox" {{$item->price ? 'checked' : ''}}>
                            <span>
                                Available for renting
                            </span>

                        </div>

                        <small id="rentNoteAvailable-{{$item->id}}" class="hidden font-bold">Note:
                            <i class="text-xs font-normal">if not checked, this item will be set for borrowing
                                only.</i>
                        </small>

                        <script>



                            document.getElementById('isAvailableForRentingCheckbox-{{$item->id}}').addEventListener('change', function () {

                                if (document.getElementById('isAvailableForRentingCheckbox-{{$item->id}}').checked) {
    document.getElementById('price-{{$item->id}}').required = true;
} else {
    document.getElementById('price-{{$item->id}}').required = false;
}
                                var updateRentingOptions = document.getElementById('rentingOptions-{{$item->id}}');
                                var updateRentNoteUnavailable = document.getElementById('rentNoteAvailable-{{$item->id}}');


                                if (this.checked) {
                                    updateRentingOptions.classList.toggle('hidden');
                                    updateRentNoteUnavailable.classList.toggle('hidden');
                                } else {
                                    updateRentingOptions.classList.toggle('hidden');
                                    updateRentNoteUnavailable.classList.toggle('hidden');
                                }
                            });
                        </script>

                        <div id="rentingOptions-{{$item->id}}" class="{{ $item->price != null ? '' : 'hidden' }}">

                            <label for="qty" class="block text-gray-700 font-medium">Price:</label>
                            <input id="price-{{$item->id}}" type="number" name="update_price" value="{{ $item->price }}"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                {{ $roles->contains('staff') ? 'disabled' : '' }} 
                                
                                
                                >

                            <div>
                                <label for="">Per:</label>
                                <select type="text" name="update_per" class="block p-2 w-full border border-gray-300 rounded "
                                    placeholder="" {{ $roles->contains('staff') ? 'disabled' : '' }}>
                                    <option value="{{$item->per}}">{{$item->per}}</option>
                                    <option value="pcs">Piece/s (pc/s)</option>
                                    <option value="pcs">Hour (hr)</option>
                                    <option value="km">Kilometer (km)</option>
                                    <option value="mi">Miles (mi)</option>
                                    <option value="m">Meters (m)</option>
                                    <option value="kg">Kilogram (kg)</option>
                                    <option value="g">Grams (g)</option>
                                    <option value="mg">Milligrams (mg)</option>
                                    <option value="cm">Centimeters (cm)</option>
                                    <option value="mm">Millimeters (mm)</option>
                                    <option value="lbs">Pounds (lbs)</option>
                                    <option value="oz">Ounces (oz)</option>
                                    <option value="l">Liters (l)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                </select>

                            </div>
                        </div>


                        <div>
                            <label for="">Assigned Personel:</label>
                            <input type="text" name="update_assigned_personel" value="{{$item->assigned_personel}}"
                                class="block p-2 border border-gray-300 rounded">
                        </div>


                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end p-4 border-t border-gray-300 space-x-2">

                <button type="button" onclick="document.getElementById('item-{{$item->id}}').classList.add('hidden')"
                    class="shadow-md px-4
                py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded-md focus:outline-none focus:ring-2
                focus:ring-gray-500">
                    Close
                </button>
                @hasrole('superadmin|admin')
                <button type="submit"
                    class="shadow-md px-4 py-2 text-white bg-blue-600 hover:opacity-50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update
                </button>
                @endhasrole
            </div>

        </div>
    </div>

</form>

<script>
    document.getElementById('update_img').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('update-img-preview');
        const oldImg = document.getElementById('old-img-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                oldImg.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');

            oldImg.classList.remove('hidden');
        }
    });
</script>