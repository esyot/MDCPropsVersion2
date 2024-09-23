@extends('layouts.header')

@section('content')

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px; 
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888; 
        border-radius: 10px; 
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }

    .truncate-text {
        width: 250px;
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis;          
    }
</style>

<div class="flex flex-col h-screen">
    <div class="flex flex-grow overflow-hidden">
        <!-- Recipients Container -->
        <div class="w-full lg:w-1/4 bg-gray-400 shadow-md overflow-y-auto">
            <h2 class="m-4 font-bold mb-4 text-2xl">Chats</h2>
            <ul class="list-none">
                @foreach($contacts as $contact)
                <a href="{{ route('chatSelected', ['contact'=>$contact->sender_name]) }}" >
                    <li class="hover:bg-gray-300 p-3 m-2 rounded-lg mb-2 cursor-pointer duration-300">
                        <div class="flex justify-between space-x-2 items-center">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden">
                                    @if($contact->img==null)
                                    <img src="{{ asset('asset/photos/user.png') }}" alt="Profile Icon" class="w-full h-full object-cover">
                                    @else
                                    <!-- Your image handling here -->
                                    @endif
                                </div>
                                <div>
                                    <h1 class="font-semibold">{{ $contact->sender_name }}</h1>
                                    <h1 id="message-latest" class="w-[250px] truncate text-gray-600">{{ $contact->content }}</h1>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <i class="text-green-500 fa-solid fa-circle"></i>
                            </div>
                        </div>
                    </li>
                </a>
                @endforeach
            </ul>
        </div>

        <!-- Messages Container -->
        <div class="lg:w-3/4 flex flex-col">
            <!-- Messages Bubble Section -->
            <div id="messages-container" class="flex flex-col flex-grow overflow-y-auto p-4 bg-blue-300 custom-scrollbar">
                @foreach($allMessages as $message)
                <div class="relative space-x-2 flex {{ $message->sender_name == $current_user_name ? 'justify-end' : 'justify-start' }} space-x-0 p-3 rounded-lg group">
                    @if($message->sender_name != $current_user_name)
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/40" alt="Profile Icon" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="space-y-2">
                        @if($message->replied_message != null)  
                        <small class="space-x-1 mb-14 p-2 flex justify-end items-center text-sm text-gray-600">
                            <i class="hover:text-gray-500 fa-solid fa-share" style="transform: scaleX(-1);"></i>
                            <h1>You replied to {{ explode(' ', $receiver_name)[0] }}</h1>
                        </small>
                        <div class="mt-20">
                            <div class="relative w-[400px]">
                                <div class="absolute bottom-[-10px] left-0 w-full bg-opacity-20 py-2 px-3 text-white bg-gray-400 rounded-tl-full rounded-tr-full rounded-s-full mt-12">
                                    <h1 class="p-1">{{ $message->replied_message }}</h1>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div onmouseover="document.getElementById('icons-{{$message->id}}').classList.remove('hidden')"
                             onmouseout="document.getElementById('icons-{{$message->id}}').classList.add('hidden')"
                             class="w-[400px] flex {{ $message->sender_name == $current_user_name ? 'justify-end' : '' }} items-center space-x-1">
                            @if($message->sender_name == $current_user_name)
                                <div id="icons-{{$message->id}}" class="items-center hidden">
                                    <form class="flex" action="{{ route('messageReacted', ['id'=>$message->id]) }}">
                                        <button type="submit" title="reply to this message" class="px-1 py-1.2 rounded-full hover:bg-gray-300">
                                            <i title="add a reaction" class="hover:text-gray-500 fa-regular fa-face-smile"></i>
                                        </button>
                                        <button onclick="handleButtonClick('{{$message->id}}', '{{$sender_name}}', '{{$receiver_name}}')" type="button" title="reply to this message" class="px-1 py-1.2 rounded-full hover:bg-gray-300">
                                            <i class="hover:text-gray-500 fa-solid fa-share" style="transform: scaleX(-1);"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                            <div class="{{ $message->sender_name == $current_user_name ? 'bg-blue-500 text-white' : 'bg-white' }} text-justify inline-block max-w-full p-4 rounded-2xl shadow-sm relative">
                                <p class="break-words max-w-full">{{ $message->content }}</p>
                                @if($message->isReacted == true)
                                    <div class="absolute bottom-[-14px] right-0 mb-1 mr-1 rounded-full w-5 h-5.8 {{ $message->sender_name == $current_user_name ? 'bg-blue-500' : 'bg-white' }} flex items-center justify-center">
                                        <i class="text-sm fa-solid fa-heart text-red-500"></i>
                                    </div>
                                @endif
                            </div>
                            @if($message->sender_name != $current_user_name)
                                <div id="icons-{{$message->id}}" class="items-center hidden">
                                    <form class="flex" action="{{ route('messageReacted', ['id'=>$message->id]) }}">
                                        <button type="submit" title="reply to this message" class="px-1 py-1.2 rounded-full hover:bg-gray-300">
                                            <i title="add a reaction" class="hover:text-gray-500 fa-regular fa-face-smile"></i>
                                        </button>
                                        <button onclick="handleButtonClick('{{$message->id}}', '{{$sender_name}}', '{{$receiver_name}}')" type="button" title="reply to this message" class="px-1 py-1.2 rounded-full hover:bg-gray-300">
                                            <i class="hover:text-gray-500 fa-solid fa-share" style="transform: scaleX(-1);"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="flex {{ $message->sender_name == $current_user_name ? 'justify-end mr-4' : 'justify-start ml-4' }} mb-2">
                            <small class="text-gray-500">{{ $message->created_at->format('g:i A') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @foreach($allMessages as $message)
                <div id="message-{{$message->id}}" class="p-2 bg-gray-200 bg-opacity-50 hidden">
                    <div class="flex justify-between">
                        <h1 class="font-semibold">Replying to {{$message->sender_name}}</h1>
                        <button class="text-2xl hover:text-white" 
                            onclick="document.getElementById('message-{{$message->id}}').classList.add('hidden'); 
                            document.getElementById('replied-message-id').value=null;">
                            &times;
                        </button>
                    </div>
                    <p>{{ $message->content }}</p>
                </div>
            @endforeach


            <div class="fixed bottom-0 right-0 bg-blue-500 p-4 w-[200px] shadow-md">
    
        <form action="{{ route('messageSend') }}" method="POST">
        <div class="flex space-x-2">
            <form action="{{ route('messageSend') }}" method="POST" class="flex items-center w-full space-x-2">
                <input type="hidden" name="replied_message_id" id="replied-message-id">
                <input type="hidden" value="{{$sender_name}}" name="sender_name" id="sender_name">
                <input type="hidden" value="{{$receiver_name}}" name="receiver_name" id="receiver_name">
                <input type="text" name="content" placeholder="Aa" class="block w-full px-3 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors">
                <button type="submit" class="w-10 h-10 rounded-full bg-teal-500 text-white hover:bg-teal-600 transition-colors">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        
        </div>
        
    </div>

    <!-- Input Section -->


<script>
    // Function to scroll to the bottom of the messages container
    function scrollToBottom() {
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Scroll to the bottom when the page loads
    window.onload = function() {
        scrollToBottom();
    }

    function handleButtonClick(currentMessageId, $sender_name, $receiver_name) {
        const targetMessageId = 'YOUR_TARGET_ID'; 
        
        const messages = document.querySelectorAll('[id^="message-"]');
        messages.forEach(message => {
            if (message.id !== 'message-' + currentMessageId) {
                message.classList.add('hidden');
                document.getElementById('replied-message-id').value = currentMessageId;
                document.getElementById('sender_name').value = $sender_name;
                document.getElementById('receiver_name').value = $receiver_name;
            } else {
                message.classList.remove('hidden');
                document.getElementById('replied-message-id').value = currentMessageId;
                document.getElementById('sender_name').value = $sender_name;
                document.getElementById('receiver_name').value = $receiver_name;
            }
        });
    }
</script>

@endsection
