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

                    <div
                        class="flex mb-2 w-full items-center p-2 space-x-2 bg-white border border-gray-300 rounded-full shadow-md">
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
            <form action="{{ route('messageNewSend') }}" method="POST" id="messageForm">
                @csrf

                <input type="hidden" id="userId" name="receiver_id" class="border border-gray-300">

                <div class="flex p-2 border border-gray-300 shadow-inner">
                    <h1>To:</h1>
                    <input id="userName" type="text" class="focus:outline-none" required>
                </div>
                <div id="no-user-selected-error" class="hidden text-red-500">
                    <h1>Select a recipient first</h1>
                </div>

                <div class="mt-2">
                    <label for="message-content">Message:</label>
                    <textarea id="message-content" name="content" placeholder="Input text here..."
                        class="block h-[300px]  border border-gray-300 w-full" required></textarea>
                </div>
                <div id="no-content-error" class="hidden text-red-500">
                    <h1>Message field cannot be empty</h1>
                </div>

                <div class="flex justify-end p-2">
                    <button type="button" onclick="handleSubmit()"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-800 text-blue-100 rounded-full">
                        Send
                    </button>
                </div>


                <script>
                    function handleSubmit() {
                        var userName = document.getElementById('userName').value;
                        var content = document.getElementById('message-content').value;

                        // Clear previous error messages
                        document.getElementById('no-user-selected-error').classList.add('hidden');
                        document.getElementById('no-content-error').classList.add('hidden');

                        if (userName !== '' && content !== '') {
                            // If userName is not empty, submit the form
                            document.getElementById('messageForm').submit();
                        } else if (content === '') {
                            // If userName is empty but content is filled, show content error
                            document.getElementById('no-content-error').classList.remove('hidden');
                        } else if (userName === '') {
                            // If both fields are empty, show the user name error
                            document.getElementById('no-user-selected-error').classList.remove('hidden');
                        }
                    }
                </script>
            </form>

        </div>
    </div>
</div>