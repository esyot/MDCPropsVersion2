<div id="property-add-modal"
    class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div id="item-add-content" class="bg-white mx-2 shadow-md max-w-full rounded ">
        <div class="flex justify-between p-1 items-center space-x-6 border-b border-gray-300">
            <h1 class="p-2 text-xl font-bold">Add Property</h1>
            <button onclick="document.getElementById('item-property-modal').classList.toggle('hidden')"
                class="text-4xl hover:text-gray-400 px-2 font-medium">&times;</button>
        </div>
        <form action="{{ route('admin.property-add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 bg-gray-100 py-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto h-[500px] custom-scrollbar">
                    <!-- Left Column: Image and Name -->
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col justify-center items-center">

                            <img id="img-preview" src="" alt="Image Preview" class="w-64 hidden">
                            <div>

                                <label for="img" class="font-medium">Image:</label>
                                <input type="file" name="img" accept="image/*" id="img"
                                    class="block w-full p-2 border border-gray-300 bg-white rounded" required>
                                <small class="font-bold">Note:
                                    <i class="text-sm font-normal">Make sure the selected file is in "jpg", "png", or
                                        "jpeg"
                                        format.</i>
                                </small>
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="title" class="font-medium">Name:</label>
                            <input type="text" name="name" placeholder="Name" title="Title"
                                class="block p-2 border border-gray-300 rounded w-full" required>
                        </div>

                        <div class="mt-2">
                            <label for="qty" class="font-medium">Quantity:</label>
                            <input type="number" name="qty" placeholder="Quantity" title="Title"
                                class="block p-2 border border-gray-300 rounded w-full" required>
                        </div>


                    </div>

                    <!-- Right Column: Renting Options -->
                    <div class="flex flex-col space-y-4">

                        <div class="mt-2">
                            <label for="category" class="font-medium">Category:</label>
                            <select name="category" title="Category" class="block p-2 border border-gray-300 rounded"
                                required>
                                @if(count($categories) > 0)
                                    <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                                        {{ $currentCategory->title }}
                                    </option>
                                @endif
                                @foreach($categories as $category)
                                    <option class="text-black" value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2 flex items-center space-x-1">
                            <input id="isAvailableForRentingCheckbox" type="checkbox" name="isAvailableForRenting">
                            <span>Available for renting</span>
                        </div>
                        <small id="rentNote" class="font-bold">Note:
                            <i class="text-sm font-normal">If unchecked, this item will be set for borrowing only.</i>
                        </small>

                        <!-- Renting Options (conditionally shown) -->
                        <div id="rentingOptions" class="flex flex-col space-y-4 hidden">
                            <div>
                                <label for="">Price:</label>
                                <input type="number" name="price" class="block p-2 border border-gray-300 rounded"
                                    placeholder="0.00">
                            </div>



                            <div>
                                <label for="">Per:</label>
                                <select type="text" name="per" class="block p-2 border border-gray-300 rounded"
                                    placeholder="">
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="hr">Hour (hr)</option>
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
                                <small id="rentNote" class="font-bold">Note:
                                    <i class="text-sm font-normal">The price will be multiplied for each day rental
                                        period.</i>
                                </small>


                            </div>
                        </div>
                        <div>
                            <label for="">Assigned Personel:</label>
                            <input type="text" name="assigned_personel" class="block p-2 border border-gray-300 rounded"
                                placeholder="Assigned personel" required>
                        </div>
                        </script>
                    </div>
                </div>

                <script>
                    var checkbox = document.getElementById('isAvailableForRentingCheckbox');
                    var rentingOptions = document.getElementById('rentingOptions');
                    var rentNote = document.getElementById('rentNote');

                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            rentingOptions.classList.remove('hidden');
                            rentNote.classList.add('hidden');
                        } else {
                            rentingOptions.classList.add('hidden');
                            rentNote.classList.remove('hidden');
                        }
                    });
                </script>
            </div>

            <!-- Modal Footer: Close and Add Buttons -->
            <div class="flex justify-end border-t border-gray-300 shadow-t-lg">
                <div class="flex p-2 space-x-1">
                    <button onclick="document.getElementById('property-add-modal').classList.toggle('hidden')"
                        class="px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 rounded">Close</button>

                    <button id="submit-btn"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('property-add-modal').addEventListener('submit', function (event) {
        document.getElementById('submit-btn').disabled = true;
        document.getElementById('submit-btn').innerText = 'Submitting';
    });
</script>