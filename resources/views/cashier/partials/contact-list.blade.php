@foreach($contacts as $contact)
    <a href="{{ route('chatSelected', ['contact' => $contact->sender_name]) }}">
        <li
            class="list-none bg-gray-100 {{ $contact->isRead == false ? 'font-bold bg-gray-300 text-black' : '' }} hover:bg-gray-300 p-3 rounded-lg mb-2 cursor-pointer duration-300">
            <div class="flex justify-between mx-2 items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden">

                        <img src="{{ asset('asset/photos/user.png') }}" alt="Profile Icon"
                            class="w-full h-full object-cover">

                    </div>
                    @php
                        if ($contact->created_at) {
                            // Convert to Carbon instance if necessary
                            $messageTime = $contact->created_at instanceof \Carbon\Carbon
                                ? $contact->created_at
                                : \Carbon\Carbon::parse($contact->created_at);

                            // Get the current time
                            $currentTime = \Carbon\Carbon::now();

                            // Calculate the time difference in minutes
                            $minutesAgo = $messageTime->diffInMinutes($currentTime);

                            // Determine the appropriate time ago string
                            if ($minutesAgo < 1) {
                                $timeAgo = 'just now';
                            } else {
                                $timeAgo = $messageTime->diffForHumans($currentTime, [
                                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                ]);
                            }
                        }
                    @endphp
                    <div>
                        <h1>{{ $contact->sender_name }}</h1>
                        <h1 class="{{ $contact->isRead == false ? 'font-bold' : '' }} w-[200px] truncate">
                            {{ $contact->content }}
                        </h1>
                        <small class="text-red-500">{{$timeAgo}} </small>

                    </div>
                </div>
                <div id="active" class="ml-6 flex justify-end">

                    <div id="active" class="text-green-500 text-[8px] fa-solid fa-circle"></div>

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
</div>
<button class="w-full p-2 text-blue-600 cursor-pointer hover:bg-blue-100 transition duration-150 ease-in-out">See
    All Messages</button>