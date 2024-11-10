@foreach($contacts as $contact)

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

    @php
        if ($contact->isLoggedOut_at) {
            // Convert to Carbon instance if necessary
            $logoutTime = $contact->isLoggedOut_at instanceof \Carbon\Carbon
                ? $contact->isLoggedOut_at
                : \Carbon\Carbon::parse($contact->isLoggedOut_at);

            // Get the current time
            $currentTime = \Carbon\Carbon::now();

            // Calculate the time difference in minutes
            $minutesAgo = $logoutTime->diffInMinutes($currentTime);

            // Determine the appropriate time ago string
            if ($minutesAgo < 1) {
                $LogoutTimeAgo = 'just logged out';
            } else {
                $LogoutTimeAgo = $logoutTime->diffForHumans($currentTime, [
                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ]);
            }
        } else {
            $LogoutTimeAgo = 'Not logged out yet';  // Default message if `isLoggedOut_at` is not set
        }
    @endphp



    <a href="{{ route('chatSelected', ['contact' => $contact->sender_id]) }}" class="flex w-full">
        <li
            class="{{ $contact->isReadByReceiver == false ? 'font-bold bg-gray-300 text-black' : '' }} hover:bg-gray-200 p-2 rounded-lg cursor-pointer duration-300">
            <div class="">
                <div class="flex items-center space-x-2 ">
                    <div class="w-10 h-10 rounded-full">
                        <div class="relative">
                            <div class="w-10 h-10">
                                <img src="{{ asset('asset/photos/user.png') }}" alt="Profile Icon"
                                    class="w-full h-full object-cover">
                            </div>


                        </div>


                    </div>

                    <div class="">
                        <h1 class="font-medium text-lg">{{ $contact->sender_name }}</h1>
                        <h1
                            class="{{ $contact->isReadByReceiver == false ? 'font-bold' : '' }} w-[200px] text-gray-500 truncate">
                            @if($contact->type == 'image')
                                {{$sender_name}} sent you a photo.
                            @elseif($contact->content == 'like')
                                {{ $contact->sender_name }}: <i class="fa-solid fa-thumbs-up text-blue-500"></i>
                            @else
                                {{ $contact->sender_name }}: {{$contact->content}}
                            @endif
                        </h1>
                        <small class="text-red-500">{{$timeAgo}} </small>

                    </div>
                    @if (Carbon\Carbon::parse($contact->isLoggedOut_at)->gt(Carbon\Carbon::parse($contact->isLoggedIn_at)))
                        @if($LogoutTimeAgo == 'just logged out')

                            <small class=""> {{$LogoutTimeAgo}}</small>
                        @else
                            <small class=""> Active {{$LogoutTimeAgo}}</small>
                        @endif
                    @else
                        <div id="active" title="Active now"
                            class="text-green-500 text-[10px] hover:opacity-50 fa-solid fa-circle">
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