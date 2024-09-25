@if(count($notifications) === 0)

    <div class="flex justify-center">
        <h1>There are no new notifications at this time.</h1>
    </div>

@endif

@foreach($notifications as $index => $notification)
    @php
        if ($notification->created_at) {
            // Convert to Carbon instance if necessary
            $notificationTime = $notification->created_at instanceof \Carbon\Carbon
                ? $notification->created_at
                : \Carbon\Carbon::parse($notification->created_at);

            // Get the current time
            $currentTime = \Carbon\Carbon::now();

            // Calculate the time difference in minutes
            $minutesAgo = $notificationTime->diffInMinutes($currentTime);

            // Determine the appropriate time ago string
            if ($minutesAgo < 1) {
                $timeAgo = 'just now';
            } else {
                $timeAgo = $notificationTime->diffForHumans($currentTime, [
                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ]);
            }
        }
    @endphp



    <div
        class="{{ $notification->isRead ? 'bg-white' : 'bg-gray-200 font-bold' }} notification-item flex items-center space-x-2 p-2 text-gray-800 cursor-pointer hover:shadow-inner hover:bg-gray-300 transition duration-150 ease-in-out border-t border-gray-300">
        <img class="w- h-10 rounded-full" src="{{ asset('storage/images/users/' . $notification->icon ) }}"
            alt="Notification Icon">
        <div class="flex flex-col">
            <a href="{{ route('isRead', ['id' => $notification->id, 'redirect_link' => $notification->redirect_link]) }}">
                <h1 class="text-xs font-bold">{{ $notification->title }}</h1>
                <span class="text-sm">
                    {{ $notification->description }}<br> <small class="font-normal text-red-500">{{ $timeAgo }}</small>
                </span>
            </a>
        </div>
    </div>
@endforeach


</div>