@extends('layouts.header')

@section('content')

<div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
    <div class="flex flex-wrap flex-grow gap-2">
        @foreach ($users as $user)
            <div
                class="flex flex-col items-center bg-blue-500 p-6 m-2 rounded-lg shadow-lg transition-transform transform hover:scale-105 flex-1 min-w-[200px] max-w-[300px]">
                <img class="w-32 h-32 border-4 border-gray-300 drop-shadow-lg rounded-full mb-4"
                    src="{{ asset('storage/images/users/' . $user->img) }}" alt="">
                <div>
                    <h1 class="text-white text-md font-semibold">Name: {{$user->name}}</h1>
                    <h1 class="text-white text-md font-semibold">Email: {{$user->email}}</h1>
                    @if($user->getRoleNames()->isNotEmpty())
                        <div class="flex space-x-1">
                            <h2 class="text-white font-medium">Role: </h2>
                            <ul>
                                @foreach($user->getRoleNames() as $role)
                                    <li class="text-white font-medium"> {{ $role }} </li>
                                @endforeach
                            </ul>
                    @else
                        <p>This user has no assigned roles.</p>
                    @endif

                    </div>
                    <div class="mt-2">
                        <button onclick="openModal('{{ $user->id }}', '{{ $user->getRoleNames()->first() }}')"
                            class="px-4 py-2 space-x-1 bg-green-100 text-green-800 hover:bg-green-500 rounded">
                            <i class="fas fa-edit fa-fw"></i><span>Edit</span>
                        </button>

                        <button class="px-4 py-2 space-x-1 bg-red-100 text-red-800 hover:bg-red-500 rounded">
                            <i class="fa-solid fa-trash fa-w"></i><span>Delete</span>
                        </button>
                    </div>

                </div>

            </div>
        @endforeach
    </div>
</div>
<!-- Modal -->
<div id="roleModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden z-50">
    <div class="bg-white px-3 py-2 rounded-lg shadow-md w-[200px]">
        <div class="flex justify-center">
            <i class="bg-orange-500 px-2 rounded-full py-2.5 fas fa-user-cog text-white"></i>
        </div>
        <div class="flex justify-center">
            <h2 class="text-xl font-semibold mb-4">Edit User Role</h2>

        </div>

        <form id="roleForm" action="{{ route('roleUpdate') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="user_id">

            <div class="ml-12 mb-4 flex flex-col justify-center flex">
                @foreach ($roles as $role)
                    <label class="flex items-center mb-2">
                        <input type="radio" name="role" value="{{ $role->name }}" class="mr-2">
                        {{ $role->name }}
                    </label>
                @endforeach
            </div>

            <div class="flex justify-between mt-4">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(userId, currentRole) {
        document.getElementById('user_id').value = userId;

        // Set the current role as checked
        const roles = document.querySelectorAll('input[name="role"]');
        roles.forEach(role => {
            role.checked = (role.value === currentRole);
        });

        document.getElementById('roleModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('roleModal').classList.add('hidden');
    }
</script>


@endsection