@foreach ($users as $user)
    <div id="card"
        class="flex flex-col items-center rounded-lg p-2 bg-gradient-to-b from-blue-500 to-blue-800 justify-center transition-transform duration-300 ease-in-out hover:scale-90">
        <img class="w-32 h-32 border-4 border-gray-300 drop-shadow-lg rounded-full mb-4"
            src="{{ asset('storage/images/users/' . $user->img) }}" alt="">
        <div>
            <h1 class="text-white text-md font-semibold">Name: {{$user->name}}</h1>
            <h1 class="text-white text-md font-semibold">Email: {{$user->email}}</h1>

            <div class="flex space-x-1">
                @if($user->getRoleNames()->isNotEmpty())
                    <h2 class="text-white font-medium">Role: </h2>
                    <ul>
                        @foreach($user->getRoleNames() as $role)
                            <li class="text-white font-medium"> {{ $role }} </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-white">This user has no assigned roles.</p>

                @endif
            </div>

            @can('can assign roles')

                <div class="flex justify-center space-x-1 mt-2">
                    <form action="{{ route('userDelete', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        <button title="Delete user" type="button"
                            onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.remove('hidden')"
                            class="px-4 py-2 space-x-1 bg-gray-500 text-gray-100 hover:opacity-50 rounded">
                            <i class="fa-solid fa-trash fa-w"></i><span>Delete</span>
                        </button>
                    </form>
                    <button title="Edit role" onclick="openModal('{{ $user->id }}', '{{ $user->getRoleNames()->first() }}')"
                        class="px-4 py-2 space-x-1 bg-blue-500 hover:opacity-50 text-blue-100 rounded">
                        <i class="fas fa-edit fa-fw"></i><span>Edit Role</span>
                    </button>


                </div>
            @endcan

        </div>


    </div>
    @include('admin.modals.user-delete-confirm')
    </form>
@endforeach


@if(count($users) == 0)
    <div class="flex w-full justify-center">
        <span class="text-red-500">
            No match found.
        </span>

    </div>
@endif