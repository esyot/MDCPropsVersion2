@if(count($allMessages) == 0)
    <div class="flex flex-wrap w-full h-full items-center justify-center">

        <p class="p-4 text-center bg-white">No conversation is made!</p>

    </div>
@endif



<!-- Messages Bubble Section -->
<div class="flex flex-col">

    @foreach($allMessages as $message)
        @if(count($allMessages) < 0)
            <div>
                <p class="p-4 bg-red-500"> no conversation is made</p>
            </div>
        @endif
        @include('admin.modals.image-preview')


        <div
            class="relative space-x-2 flex {{ $message->sender_name == $current_user_name ? 'justify-end' : 'justify-start' }} p-3 rounded-lg group">

            @if($message->sender_name != $current_user_name)
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('asset/photos/user.png')}}" alt="Profile Icon" class="w-full h-full object-cover">
                </div>
            @endif
            <div class="">
                @if($message->sender_name == $current_user_name && $message->replied_message_type != null)


                    <div class="flex flex-col">


                        <small
                            class="flex items-center space-x-1 {{ $message->sender_name == $current_user_name ? 'justify-end' : 'justify-start' }}">
                            <i class="hover:text-gray-500 fa-solid fa-share" style="transform: scaleX(-1);"></i>
                            @if($message->replied_message_name == $current_user_name)
                                <h1>You replied to yourself</h1>
                            @endif
                            @if($message->replied_message_name != $current_user_name)
                                <h1>You replied to {{ explode(' ', $message->replied_message_name)[0]}}</h1>
                            @endif

                        </small>

                        <div class="relative w-[400px]">

                            <div
                                class="{{ $message->sender_name == $current_user_name ? 'float-end' : 'float-start' }} text-justify bg-opacity-30 text-blue-500 inline-block max-w-full opacity-50 rounded-2xl shadow-sm relative">

                                @if($message->replied_message_type == 'text')
                                    <div class="bg-gray-200 p-2 text-gray-800 rounded-xl">
                                        <h1>{{ $message->replied_message }}</h1>
                                    </div>


                                @endif

                                @if($message->replied_message == 'like')
                                    <div
                                        class="{{ $message->sender_name == $current_user_name ? 'float-end' : 'float-start' }} text-justify bg-opacity-30 text-blue-500 inline-block max-w-full opacity-50 rounded-2xl relative">


                                        <i class="text-[120px] fa-solid fa-thumbs-up"></i>
                                    </div>

                                @endif
                                @if($message->replied_message_type == 'image')
                                    <img width="200" src="{{ asset('storage/images/' . $message->replied_message) }}" alt="">

                                @endif

                            </div>



                        </div>

                    </div>


                @endif


                <!-- for contacts -->

                @if($message->sender_name != $current_user_name && $message->replied_message != null)


                    <div class="flex flex-col">


                        <small
                            class="flex items-center space-x-1 {{ $message->sender_name == $current_user_name ? 'justify-end' : 'justify-start' }}">
                            <i class="hover:text-gray-500 fa-solid fa-share" style="transform: scaleX(-1);"></i>
                            @if($message->replied_message_name != $current_user_name)
                                <h1>{{explode(' ', $message->replied_message_name)[0] }} replied to itself</h1>
                            @endif
                            @if($message->replied_message_name == $current_user_name)
                                <h1>{{ explode(' ', $receiver_name)[0]}} replied to you</h1>
                            @endif

                        </small>

                        <div class="relative w-[400px]">


                            @if($message->replied_message == 'like')
                                <div
                                    class="{{ $message->sender_name == $current_user_name ? 'float-end' : 'float-start' }} text-blue-500 text-justify bg-opacity-30 inline-block max-w-full shadow-sm relative">


                                    <i class="text-[120px] fa-solid fa-thumbs-up"></i>


                            @endif

                                @if($message->replied_message != 'like')
                                    <div
                                        class="{{ $message->sender_name == $current_user_name ? 'float-end' : 'float-start' }} text-justify bg-opacity-30 bg-gray-200 inline-block max-w-full p-4 rounded-2xl shadow-sm relative">


                                        <h1 class="p-1 text-gray-500 break-words">{{ $message->replied_message }}</h1>

                                @endif



                                </div>
                            </div>

                        </div>


                @endif
                    <div onmouseover="document.getElementById('icons-{{$message->id}}').classList.remove('hidden')"
                        onmouseout="document.getElementById('icons-{{$message->id}}').classList.add('hidden')"
                        class="w-[400px] flex {{ $message->sender_name == $current_user_name ? 'justify-end' : '' }} items-center space-x-1">


                        @if($message->sender_name == $current_user_name)
                            <div id="icons-{{$message->id}}" class="items-center hidden">
                                <form class="flex" action="{{ route('messageReacted', ['id' => $message->id]) }}">
                                    <button type="submit" title="React"
                                        class="px-1 py-1.2 rounded-full hover:bg-blue-500 hover:text-blue-100">
                                        <i class="fa-regular fa-face-smile"></i>
                                    </button>

                                    <button title="Reply" class="px-1 py-1.2 rounded-full hover:bg-blue-500 hover:text-blue-100"
                                        onclick="handleButtonClick('{{ $message->id }}', '{{ $message->sender_name }}', '{{ $message->type }}')"
                                        type="button">
                                        <i class="fa-solid fa-share" style="transform: scaleX(-1);"></i>
                                    </button>
                                </form>
                            </div>
                        @endif




                        <div
                            class="{{ $message->sender_name == $current_user_name ? 'text-blue-500' : 'text-blue-500' }} text-justify inline-block max-w-full rounded-2xl drop-shadow-sm relative">

                            @if($message->type == 'text')


                                <div
                                    class="{{ $message->sender_name == $current_user_name ? 'bg-blue-500 text-white' : 'bg-white text-black' }} p-4  rounded-xl">
                                    <h1 class="break-words">{{ $message->content }}</h1>
                                </div>
                            @elseif($message->type == 'sticker')

                                <div
                                    class="{{ $message->sender_name == $current_user_name ? 'float-end' : 'float-start' }} text-justify bg-opacity-30 text-blue-500 inline-block max-w-full rounded-2xl relative">


                                    <i class="text-[120px] fa-solid fa-thumbs-up"></i>
                                </div>

                            @elseif($message->type == 'image')
                                <div
                                    class="flex {{ $message->receiver_name == $current_user_name ? 'items-start' : 'items-end' }} flex-col">


                                    <div
                                        onclick="document.getElementById('image-preview-{{ $message->id }}').classList.remove('hidden')">
                                        <img class="rounded-xl shadow-md" width="300"
                                            src="{{ asset('storage/images/' . $message->img) }}" alt="">

                                    </div>
                                    @if($message->content != null)
                                        <div
                                            class="{{ $message->sender_name == $current_user_name ? 'bg-blue-500 text-white' : 'bg-white text-black' }} p-4  rounded-xl">
                                            <h1 class="break-words">{{ $message->content }}</h1>
                                        </div>
                                    @endif

                                </div>

                            @endif


                            @if($message->isReacted == true)
                                <div
                                    class="absolute bottom-[-14px] right-0 mb-1 mr-1 rounded-full w-5 h-5.8 {{ $message->sender_name == $current_user_name ? 'bg-blue-500' : 'bg-white' }} flex items-center justify-center">
                                    <i class="text-sm fa-solid fa-heart text-red-500"></i>
                                </div>
                            @endif
                        </div>


                        <!-- icons left -->

                        @if($message->sender_name != $current_user_name)
                            <div id="icons-{{$message->id}}" class="items-center hidden">
                                <form class="flex" action="{{ route('messageReacted', ['id' => $message->id]) }}">
                                    <button type="submit" title="React"
                                        class="px-1 py-1.2 rounded-full hover:bg-blue-500 hover:text-blue-100">
                                        <i class="fa-regular fa-face-smile"></i>
                                    </button>

                                    <button title="Reply" class="px-1 py-1.2 rounded-full hover:bg-blue-500 hover:text-blue-100"
                                        onclick="handleButtonClick('{{ $message->id }}', '{{ $message->sender_name }}', '{{ $message->type }}')"
                                        type="button">
                                        <i class="fa-solid fa-share" style="transform: scaleX(-1);"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div
                        class="flex {{ $message->sender_name == $current_user_name ? 'justify-end mr-4' : 'justify-start ml-4' }} mb-2">
                        <small class="text-gray-500">{{ $message->created_at->format('g:i A') }}</small>
                    </div>
                </div>
            </div>

    @endforeach
    </div>