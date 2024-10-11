  <!-- Modal for user delete confirmation -->
  <div id="userDeleteConfirm-{{$user->id}}"
                class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
                <div class="bg-white p-6 flex justify-center items-center flex-col rounded drop-shadow-lg">
                    <div>
                        <i class="fa-solid fa-question text-white px-4 py-3 bg-yellow-500 rounded-full drop-shadow-lg"></i>
                    </div>

                    <div class="mt-2">
                        <h1>Are you sure to delete this user?</h1>
                    </div>

                    <div class="space-x-1 mt-3">
                        <button type="submit" class="text-lg hover:underline text-green-300 hover:text-green-500">Yes,
                            proceed.</button>
                        <button type="button"
                            onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.add('hidden')"
                            class="text-lg hover:underline text-red-300 hover:text-red-500">No,
                            cancel.</button>
                    </div>



                </div>
            </div>