@if (session()->has('success'))
    <div id="success-modal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-2 rounded shadow-md">
            <div class="flex flex-col items-center space-y-2 justify-center">
                <div>
                    <i class="fas fa-circle-check text-green-500 fa-2xl"></i>
                </div>

                <h1>{{session('success')}}</h1>
            </div>
            <footer class="flex justify-center mt-2">
                <button onclick="document.getElementById('success-modal').classList.toggle('hidden')"
                    class="px-4 py-2 border border-gray-300 hover:opacity-50 rounded">Close</button>
            </footer>
        </div>
    </div>
@endif