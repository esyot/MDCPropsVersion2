@extends('admin.layouts.messenger-header')
@section('content')

<!-- Main Content -->
<div id="contacts" class="main-content flex flex-1 overflow-y-auto transition-all duration-300 ease-in-out">

    <div id="chats" class="flex w-80 items-center flex-col shadow">

        <div id="title" class="mt-2 text-2xl font-bold">
            <h1>Chats</h1>
        </div>
        <div>
            <div class="relative my-2 mx-1 mb-4">
                <form hx-get="{{ route('contacts') }}" hx-trigger="input" hx-swap="innerHTML" hx-target="#contact-list"
                    class="flex justiify-around px-2 items-center bg-white rounded-full">

                    <div class="p-2">
                        <i class="fas fa-search text-gray-500"></i>
                    </div>
                    <input type="text" title="Search" name="searchValue" placeholder="Search contact"
                        class="bg-transparent mr-8 focus:outline-none">

                </form>

            </div>

        </div>
        <div id="contacts" class="mb-2 flex p-2 overflow-y-auto custom-scrollbar">
            <ul id="contact-list" class="list-none">

                @include('admin.partials.contact-list')

            </ul>

        </div>


    </div>
    <!-- Box C -->
    <div class="w-full flex flex-col">

        <div id="header-messenger" class="bg-blue-500 shadow-md p-2">
            <div class="items-center flex ">
                <img class="w-12 h-12 p-2 drop-shadow-md" src="{{ asset('asset/photos/user.png') }}" alt="">
                <h1 class="text-xl text-white font-semibold">
                    {{ $receiver_name }}
                </h1>

            </div>

            <div class="relative flex w-full h-full">
                <div class="absolute inset-0 top-[17rem] flex items-center justify-center">
                    <img class="bg-blue-500 rounded-full p-2 flex w-[300px] opacity-10 drop-shadow-2xl"
                        src="{{asset('asset/photos/logo.png')}}" alt="">
                </div>
            </div>


        </div>
        <!-- <div id="loader"
            class="rounded bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center z-50 hidden">
            <img src="{{ asset('asset/loader/loading.gif') }}" alt="Loading..." class="w-16 h-16">
        </div> -->


        <div id="messages-container" class="flex flex-1 overflow-y-auto flex-col custom-scrollbar h-64">
            @include('admin.partials.message-bubble')
        </div>

        <a hx-get="{{ route('messageBubble', ['receiver_name' => $receiver_name])}}" hx-swap="innerHTML"
            hx-trigger="every 1s" hx-target="#messages-container"></a>


        <div class="bg-blue-500">
            @foreach($allMessages as $message)
                <div id="message-to-reply-{{$message->id}}" class="p-2 bg-white shadow-md hidden">
                    <div class="flex justify-between">


                        @if($message->sender_name == $current_user_name)
                            <h1 class="font-semibold">Replying to yourself</h1>
                        @else
                            <h1 class="font-semibold">Replying to {{$message->sender_name}}</h1>
                        @endif
                        </h1>
                        <button class="text-2xl hover:text-gray-400" onclick="messageReplyViewClose('{{ $message->id}}')">
                            &times;
                        </button>
                    </div>

                    @if($message->type == 'sticker')
                        <p>
                            sticker
                        </p>
                    @elseif($message->type == 'image')
                        <p>image</p>

                    @else
                        <p>{{ $message->content }}</p>
                    @endif
                </div>

            @endforeach


            <div id="footer-messenger" class="flex space-x-2 p-4">

                <form id="myForm" action="{{ route('messageSend') }}" method="POST"
                    class=" flex items-end w-full space-x-4">

                    @csrf


                    <input type="file" id="fileInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                    <button type="button" title="Image"
                        class="rounded-full hover:bg-blue-300 transition-transform duration-300 ease-in-out transform hover:scale-110 drop-shadow text-xl px-2 py-1 hover:text-white text-gray-100"
                        onclick="document.getElementById('fileInput').click();">
                        <i class="fa-solid fa-image"></i>
                    </button>




                    <input type="hidden" name="replied_message_id" id="replied-message-id">
                    <input type="hidden" name="replied_message_name" id="replied-message-name">
                    <input type="hidden" name="replied_message_type" id="replied-message-type">
                    <input type="hidden" value="{{$sender_name}}" name="sender_name" id="sender_name">
                    <input type="hidden" value="{{$receiver_name}}" name="receiver_name" id="receiver_name">
                    <input type="hidden" id="image-data" name="image-data" />

                    <div class="relative w-full">
                        <div class="flex items-end space-x-2 rounded-lg">

                            <div class="flex flex-col flex-1 bg-white rounded-xl">
                                <div class="flex flex-wrap items-start">
                                    <div class="flex flex-wrap">

                                        <div id="image-container" class="flex justify-between">


                                        </div>
                                    </div>
                                    <div id="img-preview-x"
                                        class="absolute left-[9.5rem] top-1 text-red-800 font-semibold hidden">

                                        <button type="button" onclick="imagePreviewClose()" title="Close"
                                            class="shadow-md border border-gray-300 w-8 h-8 flex items-center justify-center rounded-full bg-white hover:bg-gray-200 text-gray-600 hover:text-gray-800 focus:outline-none">
                                            <span class="mb-1 text-2xl font-semibold">&times;</span>
                                        </button>



                                    </div>

                                </div>

                                <div class="flex flex-col p-2 bg-white rounded-full">

                                    <input autocomplete="off" type="text" id="content" name="content"
                                        class="w-full px-2 focus:outline-none" placeholder="Aa">

                                </div>

                            </div>
                            <div class="px-2">

                                <button type="submit" id="sendButton" title="Send"
                                    class="rounded-full hover:bg-blue-300 transition-transform duration-300 ease-in-out transform hover:scale-110 drop-shadow flex items-center text-xl text-2xl px-2 py-2 hover:text-white text-blue-100 mb-0.5">
                                    <i id="sendIcon" class="fa-solid fa-thumbs-up"></i>
                                </button>

                            </div>

                        </div>
                    </div>
            </div>


            </form>

        </div>
    </div>
</div>
</div>

<script>

    function scrollToBottom() {
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }


    window.onload = function () {
        scrollToBottom();
        document.getElementById('content').focus();
    }



    function imagePreviewClose() {

        document.getElementById('image-container').innerHTML = '';
        document.getElementById('img-preview-x').classList.add('hidden');
        document.getElementById('sendIcon').classList.remove('fa-paper-plane');
        document.getElementById('sendIcon').classList.add('fa-thumbs-up');
        document.getElementById('content').focus();

    }

    function updateIcon() {
        const contentInput = document.getElementById('content');
        const sendIcon = document.getElementById('sendIcon');



        if (contentInput.value.trim() === '') {
            // Input is empty, show thumbs up icon
            sendIcon.classList.remove('fa-paper-plane');
            sendIcon.classList.add('fa-thumbs-up');
        } else {
            // Input has value, show paper plane icon
            sendIcon.classList.remove('fa-thumbs-up');
            sendIcon.classList.add('fa-paper-plane');
        }
    }

    // Attach the updateIcon function to the input's input event
    document.getElementById('content').addEventListener('input', updateIcon);


    updateIcon();




    function messageReplyViewClose(id) {


        const messageView = `message-to-reply-${id}`;

        document.getElementById('replied-message-id').value = '';
        document.getElementById('replied-message-name').value = '';
        document.getElementById('replied-message-type').value = '';
        document.getElementById(messageView).classList.add('hidden');

        //changes icon



        document.getElementById('content').focus();

        document.getElementById('sendIcon').classList.remove('fa-paper-plane');
        document.getElementById('sendIcon').classList.add('fa-thumbs-up');
    }



    function updateImageContainerMargin() {
        var imageContainer = document.getElementById('image-container');
        if (imageContainer.innerHTML.trim() !== '') {
            imageContainer.classList.add('m-0');
            imageContainer.classList.remove('m-2');
        } else {


        }
    }

    updateImageContainerMargin();

    function previewImage(event) {
        const file = event.target.files[0];

        // Check if file is selected and is an image
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('w-40', 'image-preview', 'rounded-lg', 'shadow', 'm-2');
                document.getElementById('image-container').innerHTML = '';
                document.getElementById('image-container').appendChild(img);

                // Set the base64 data URL to the hidden input
                document.getElementById('image-data').value = e.target.result;

                document.getElementById('img-preview-x').classList.remove('hidden');

                document.getElementById('sendIcon').classList.add('fa-paper-plane');
                document.getElementById('sendIcon').classList.remove('fa-thumbs-up');


            };

            reader.readAsDataURL(file);
        }
    }

    // Function to generate a unique identifier
    function generateUniqueId() {
        return 'xxxxxx'.replace(/[x]/g, function () {
            var r = Math.random() * 16 | 0, v = r.toString(16);
            return v;
        });
    }

    // Handle paste event for image files
    document.getElementById('content').addEventListener('paste', function (event) {
        event.preventDefault();

        document.getElementById('content').focus();

        if (event.clipboardData && event.clipboardData.items) {
            const items = event.clipboardData.items;

            for (const item of items) {
                if (item.type.startsWith('image/')) {
                    const file = item.getAsFile();

                    if (file) {
                        // Generate a unique identifier
                        const uniqueId = generateUniqueId();

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('w-40', 'image-preview', 'rounded-lg', 'shadow', 'm-2');
                            document.getElementById('image-container').innerHTML = '';
                            document.getElementById('image-container').appendChild(img);

                            // Set the base64 data URL to the hidden input
                            document.getElementById('image-data').value = e.target.result;

                            document.getElementById('img-preview-x').classList.remove('hidden');
                            sendIcon.classList.remove('fa-thumbs-up');
                            sendIcon.classList.add('fa-paper-plane');

                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    });







    // Global variable to track the currently open element
    let currentlyVisibleElementId = null;

    function handleButtonClick(currentMessageId, replied_message_name, replied_message_type) {



        const newElementId = `message-to-reply-${currentMessageId}`;

        // Hide the currently visible element if it's different from the new one
        if (currentlyVisibleElementId && currentlyVisibleElementId !== newElementId) {
            const currentlyVisibleElement = document.getElementById(currentlyVisibleElementId);
            if (currentlyVisibleElement) {
                currentlyVisibleElement.classList.add('hidden');
            }
        }

        // Show the new element
        const newElement = document.getElementById(newElementId);
        if (newElement) {
            newElement.classList.remove('hidden');
            document.getElementById('content').focus()
        }

        // Update the currently visible element ID
        currentlyVisibleElementId = newElementId;

        // Optionally, update form fields
        document.getElementById('replied-message-id').value = currentMessageId;
        document.getElementById('replied-message-name').value = replied_message_name;
        document.getElementById('replied-message-type').value = replied_message_type;
        document.getElementById('content').focus();
    }


</script>

@endsection