<div id="message-new" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="p-2 bg-white w-[400px] rounded">

        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold px-2">Send Message</h1>
            <button type="button" onclick="document.getElementById('message-new').classList.add('hidden')"
                class="text-6xl flex font-thin">&times;</button>
        </div>

        @php
            $value = '';
        @endphp

        <div class="px-2 py-2 space-y-2">
            <div class="flex flex-col items-center relative">
                <form hx-get="{{ route('searchContact') }}" hx-trigger="input" hx-target="#list" hx-swap="innerHTML">

                    <div class="flex items-center p-2 space-x-2 bg-white border border-gray-300 rounded-full shadow-md">
                        <i class="fas fa-magnifying-glass"></i>
                        <input oninput="document.getElementById('results').classList.remove('hidden')" type="text"
                            name="search" placeholder="Search contacts" class="w-full focus:outline-none">
                    </div>
                </form>


                <div id="results" class="absolute w-full mt-12 bg-white border border-gray-300 rounded shadow hidden">
                    <div id="list" class="max-h-60 overflow-y-auto">
                        @include('admin.partials.contacts-list')
                    </div>
                </div>
            </div>
            <form action="{{ route('messageNewSend') }}" method="POST">
                @csrf

                <input type="hidden" id="userName" name="receiver_name" class="border border-gray-300">

                <div>
                    <label for="">Message:</label>
                    <textarea name="content" placeholder="Input text here..."
                        class="block h-[300px] border border-gray-300 w-full"></textarea>
                </div>
        </div>

        <div class="flex justify-end space-x-1 p-2">
            <button class="px-3 py-2 bg-blue-500 hover:bg-blue-800 text-blue-100 rounded-full">Send</button>
            <button type="button" onclick="document.getElementById('message-new').classList.add('hidden')"
                class="px-3 py-2 bg-gray-500 hover:bg-gray-800 text-gray-100 rounded-full">Cancel</button>
        </div>
        </form>
    </div>
</div>