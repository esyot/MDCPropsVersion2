@foreach($contacts as $contact)
    @php
        // Get the current user's ID using Auth::user()->id
        $currentUserId = Auth::user()->id;
        // Check if the current user is the sender or receiver
        $isSender = $contact->sender_id == $currentUserId;
        $isReceiver = $contact->receiver_id == $currentUserId;
    @endphp

    <a href="{{ route('chatSelected', ['contact' => $contact->sender_id]) }}" class="flex ">
        <li
            class="{{ $contact->isReadByReceiver == false && $isReceiver ? 'font-bold bg-gray-300 text-black' : '' }} w-full hover:bg-gray-200  rounded-lg cursor-pointer duration-300 ">
            <div class="flex items-center w-full">
                <div class="w-12 m-2">
                    <!-- Fixed size for the profile image -->
                    <img src="{{ asset('asset/photos/user.png') }}" alt="Profile Icon"
                        class="h-[40px] w-[40px] rounded-full">
                </div>
                <div class="flex items-center justify-between w-full">
                    <div>
                        <h1 class="font-medium text-lg">
                            {{ $isSender ? 'You' : $contact->sender_name }}
                        </h1>
                        <h1
                            class="{{ $contact->isReadByReceiver == false && $isReceiver ? 'font-bold' : '' }} w-[200px] text-gray-500 truncate">
                            @if($contact->type == 'image')
                                {{ $contact->sender_name }} sent you a photo.
                            @elseif($contact->content == 'like')
                                {{ $contact->sender_name }}: <i class="fa-solid fa-thumbs-up text-blue-500"></i>
                            @else
                                {{ $contact->sender_name }}: {{ $contact->content }}
                            @endif
                        </h1>

                        <small
                            class="text-red-500">{{ \Carbon\Carbon::parse($contact->created_at)->diffForHumans() }}</small>
                    </div>

                    @if ($contact->isLoggedIn_at && \Carbon\Carbon::parse($contact->isLoggedIn_at)->gt(\Carbon\Carbon::parse($contact->isLoggedOut_at ?? '1970-01-01')))
                        <div id="active" title="Active now"
                            class="text-green-500 text-[10px] mr-2 hover:opacity-50 fa-solid fa-circle">
                        </div>
                    @endif
                </div>
            </div>
        </li>
    </a>

@endforeach

@if(count($contacts) == 0)
    <div class="m-2 bg-transparent">
        <h1 class="text-center">No match found.</h1>
    </div>
@endif