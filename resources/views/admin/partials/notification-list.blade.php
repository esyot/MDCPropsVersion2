@if(count($notifications) == null)

    <div class="flex justify-center">
        <h1>There are no new notifications at this time.</h1>
    </div>

@endif

@foreach($notifications as $index => $notification)
    @php
        if ($notification->created_at) {

            $notificationTime = $notification->created_at instanceof \Carbon\Carbon
                ? $notification->created_at
                : \Carbon\Carbon::parse($notification->created_at);

            $currentTime = \Carbon\Carbon::now();

            $minutesAgo = $notificationTime->diffInMinutes($currentTime);

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
        class="{{ in_array(Auth::user()->id, $notification->isReadBy) ? 'bg-white' : 'bg-gray-200 font-bold' }} notification-item flex items-center space-x-2 p-2 text-gray-800 cursor-pointer hover:shadow-inner hover:bg-gray-300 transition duration-150 ease-in-out border-t border-gray-300">
        <img class="border-2 border-gray-600 h-[40px] w-[40px] rounded-full"
            src="{{ Storage::exists('public/images/users/' . $notification->icon) ? asset('storage/images/users/' . $notification->icon) : asset('asset/photos/user.png') }}"
            alt="User Image">


        <div class="flex flex-col">
            <a
                href="{{ route('isRead', ['id' => $notification->id, 'redirect_link' => $notification->redirect_link, 'role' => 'admin']) }}">
                <h1 class="text-xs font-bold">{{ $notification->title }}</h1>
                <span class="text-sm">
                    @if ($notification->user_id == Auth::user()->id)
                        You
                    @else
                        {{$notification->user->name}}
                    @endif
                    {{ $notification->description }}<br> <small class="font-normal text-red-500">{{ $timeAgo }}</small>
                </span>
            </a>
        </div>
    </div>
@endforeach

</div>