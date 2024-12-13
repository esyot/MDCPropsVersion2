<!-- Modal Container -->
@foreach ($properties as $property)


<!-- Confirmation delete -->

<div id="property-delete-{{$property->id}}"
    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">
    <div class="bg-white rounded shadow-md">
        <div class="bg-red-500 py-1 rounded-t">

        </div>
        <header class="flex px-4 py-2 items-center space-x-2 border-b">
            <i class="fas fa-trash text-2xl bg-red-500 p-2 px-3.5 rounded-full text-red-100"></i>
            <section>
                <h1 class="text-xl font-medium">Delete Confirmation</h1>
                <h1>Are you sure to delete this property?</h1>
                <small class="font-bold">
                    Note:
                    <i class="font-normal">This action cannot be undone.</i>
                </small>
            </section>

        </header>
        <footer class="bg-gray-100 rounded-b flex justify-end space-x-1 p-2">
            <button type="button"
                onclick="document.getElementById('property-delete-{{$property->id}}').classList.toggle('hidden')"
                class="px-4 py-2 border border-gray-300 text-gray-800 hover:opacity-50 rounded">
                No
            </button>
            <a href="{{ route('admin.property-delete', ['id' => $property->id]) }}"
                class="px-4 py-2 bg-red-500 text-red-100 hover:opacity-50 rounded">
                Yes
            </a>
        </footer>
    </div>
</div>
<!-- /Confirmation delete -->

<form id="property-preview-{{$property->id}}" action="{{ route('admin.property-update', ['id' => $property->id]) }}" method="POST" 

        class="fixed inset-0 flex items-center justify-center z-40 bg-gray-800 bg-opacity-70 hidden">
    @csrf

 
        <!-- Modal Content -->
        <div class="bg-white w-[500px] mx-2 rounded-lg shadow-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-300">
                <h1 class="text-xl font-semibold text-gray-900">Property Preview</h1>

                @hasrole('superadmin|admin')
                <button onclick="document.getElementById('property-delete-{{$property->id}}').classList.toggle('hidden')" type="button" title="delete this property" class="hover:opacity-50">
                    <i class="fas fa-trash text-red-500"></i>
                </button>
                @endhasrole
            </div>


            <!-- Modal Body -->
            <div class="p-4 flex space-x-4 bg-gray-100">
                <!-- Image Section -->
                <div class="flex flex-col justify-center items-center space-y-5">

                    <img id="old-img-preview"
                        src="{{ asset('storage/images/categories/' . $property->category->folder_name  . '/' . $property->img) }}"
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
                        <input type="text" name="update_name" value="{{ $property->name }}"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>

                        <label for="qty" class="block text-gray-700 font-medium">Quantity:</label>
                        <input type="number" name="update_qty" value="{{ $property->qty }}"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>

            

                        <label for="update_category" class="font-medium">Category:</label>
                        <select name="update_category"
                            class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            {{ $roles->contains('staff') ? 'disabled' : '' }}>
                            <option value="{{ $property->category->id }}">{{ $property->category->title }}</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>

                        <div class="flex items-center space-x-1">
                            <input id="isAvailableForRentingCheckbox-{{$property->id}}" name="isAvailableForRenting"
                                type="checkbox" {{$property->price ? 'checked' : ''}}
                                {{ $roles->contains('staff') ? 'disabled' : '' }} 
                                >
                            <span>
                                Available for renting
                            </span>

                        </div>

                        <small id="rentNoteAvailable-{{$property->id}}" class="hidden font-bold">Note:
                            <i class="text-xs font-normal">if not checked, this item will be set for borrowing
                                only.</i>
                        </small>

                        <script>



                            document.getElementById('isAvailableForRentingCheckbox-{{$property->id}}').addEventListener('change', function () {

                                if (document.getElementById('isAvailableForRentingCheckbox-{{$property->id}}').checked) {
                                        document.getElementById('price-{{$property->id}}').required = true;
                                    } else {
                                        document.getElementById('price-{{$property->id}}').required = false;
                                    }
                                var updateRentingOptions = document.getElementById('rentingOptions-{{$property->id}}');
                                var updateRentNoteUnavailable = document.getElementById('rentNoteAvailable-{{$property->id}}');


                                if (this.checked) {
                                    updateRentingOptions.classList.toggle('hidden');
                                    updateRentNoteUnavailable.classList.toggle('hidden');
                                } else {
                                    updateRentingOptions.classList.toggle('hidden');
                                    updateRentNoteUnavailable.classList.toggle('hidden');
                                }
                            });
                        </script>

                        <div id="rentingOptions-{{$property->id}}" class="{{ $property->price != null ? '' : 'hidden' }}">

                            <label for="qty" class="block text-gray-700 font-medium">Price:</label>
                            <input id="price-{{$property->id}}" type="number" name="update_price" value="{{ $property->price }}"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                {{ $roles->contains('staff') ? 'disabled' : '' }} 
                                
                                
                                >

                            <div>
                                <label for="">Per:</label>
                                <select type="text" name="update_per" class="block p-2 w-full border border-gray-300 rounded "
                                    placeholder="" {{ $roles->contains('staff') ? 'disabled' : '' }}>
                                    <option value="{{$property->per}}">{{$property->per}}</option>
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
                            <input type="text" name="update_assigned_personel" value="{{$property->assigned_personel}}" class="block p-2 border border-gray-300 rounded"
                                placeholder="Assigned personel" required
                                {{ $roles->contains('staff') ? 'disabled' : '' }} 
                                
                                >
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end p-4 border-t border-gray-300 space-x-2">

                <button type="button" onclick="document.getElementById('property-preview-{{$property->id}}').classList.add('hidden')"
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

@endforeach