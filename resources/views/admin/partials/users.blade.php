@foreach ($users as $user)
    <div id="card"
        class="flex flex-col items-center rounded-lg p-2 bg-blue-500 justify-center transition-transform duration-300 ease-in-out hover:scale-90">
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
                    <button onclick="openModal('{{ $user->id }}', '{{ $user->getRoleNames()->first() }}')"
                        class="px-4 py-2 space-x-1 bg-blue-300 hover:text-blue-100 hover:bg-blue-800 rounded">
                        <i class="fas fa-edit fa-fw"></i><span>Edit Role</span>
                    </button>

                    <form action="{{ route('userDelete', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        <button type="button"
                            onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.remove('hidden')"
                            class="px-4 py-2 space-x-1 bg-red-300 hover:text-red-100 hover:bg-red-500 rounded">
                            <i class="fa-solid fa-trash fa-w"></i><span>Delete</span>
                        </button>
                </div>
            @endcan

        </div>


    </div>
    @include('admin.modals.user-delete-confirm')
    </form>
@endforeach