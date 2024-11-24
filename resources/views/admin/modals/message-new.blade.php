<div id="message-new" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white w-[400px] rounded shadow-md">
        <div class="py-1 bg-blue-500 rounded-t">

        </div>
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold px-2">Send Message</h1>
            <button type="button" onclick="document.getElementById('message-new').classList.add('hidden')" title="Close"
                class="text-2xl flex font-bold px-2 hover:opacity-50">&times;</button>
        </div>

        @php
            $value = '';
        @endphp

        <div class="px-2 py-2 space-y-2">
            <div class="flex flex-col">
                <form hx-get="{{ route('searchContact') }}" hx-trigger="input" hx-target="#list" hx-swap="innerHTML">

                    <div id="searchUserInput"
                        class="flex border border-gray-300 shadow-inner items-center p-2 space-x-2">
                        <i class="fas fa-magnifying-glass"></i>
                        <input oninput="document.getElementById('results').classList.remove('hidden')" type="text"
                            name="search" placeholder="Search contacts" class="w-full focus:outline-none">
                    </div>
                </form>


                <div id="results" class="absolute mt-12 bg-white border border-gray-300 rounded shadow hidden">
                    <div id="list" class="max-h-60  overflow-y-auto">

                        @include('admin.partials.contacts-list')
                    </div>
                </div>
            </div>
            <form action="{{ route('messageNewSend') }}" method="POST" id="messageForm">
                @csrf

                <input type="hidden" id="userId" name="receiver_id" class="border border-gray-300">

                <div id="userNameInput"
                    class="flex border border-gray-300 shadow-inner items-center p-2 space-x-2 hidden">
                    <i class="fas fa-magnifying-glass"></i>

                    <div id="userName" class="focus:outline-none">
                    </div>
                </div>
                <script>
                    function removeRecipient() {
                        document.getElementById('userName').innerHTML = '';
                        document.getElementById('userId').value = null;
                        document.getElementById('userNameInput').classList.toggle('hidden');
                        document.getElementById('searchUserInput').classList.toggle('hidden');
                    }


                </script>
                <div id="no-user-selected-error" class="hidden text-red-500">
                    <h1>Select a recipient first</h1>
                </div>

                <div class="mt-2">
                    <label for="message-content">Message:</label>
                    <textarea id="message-content" name="content" placeholder="Input text here..."
                        class="block h-[300px] border border-gray-300 w-full" required></textarea>
                </div>
                <div id="no-content-error" class="hidden text-red-500">
                    <h1>Message field cannot be empty</h1>
                </div>

                <div class="flex justify-end mt-2">
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