@extends('admin.layouts.header')

@section('content')

<div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
    <div class="flex flex-wrap flex-grow gap-2">

        @hasrole('admin')
        <div onclick="document.getElementById('userAddModal').classList.remove('hidden')" class=" flex flex-col items-center bg-blue-500 m-2 rounded-lg shadow-lg transition-transform transform
            hover:scale-105 flex-1 min-w-[200px] max-w-[300px]">

            <div class="bg-gray-100 hover:bg-gray-300 hover:text-gray-400 w-full rounded-t-lg">

                <div class="flex space-x-1 justify-center">
                    <div class="p-6">
                        <i class="fas fa-plus text-8xl "></i>

                    </div>
                </div>
            </div>
            <div class="mt-2">
                <h1 class="text-xl text-white">
                    Add new user
                </h1>

            </div>

            <div>

            </div>

        </div>
        @endhasrole

        @foreach ($users as $user)
            <div
                class="flex flex-col items-center bg-blue-500 p-6 m-2 rounded-lg shadow-lg transition-transform transform hover:scale-105 flex-1 min-w-[200px] max-w-[300px]">
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

                    @hasrole('admin')

                    <div class="flex justify-center space-x-1 mt-2">
                        <button onclick="openModal('{{ $user->id }}', '{{ $user->getRoleNames()->first() }}')"
                            class="px-4 py-2 space-x-1 bg-blue-300 hover:text-blue-100 hover:bg-blue-800 rounded">
                            <i class="fas fa-edit fa-fw"></i><span>Edit</span>
                        </button>

                        <form action="{{ route('userDelete', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            <button type="button"
                                onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.remove('hidden')"
                                class="px-4 py-2 space-x-1 bg-red-300 hover:text-red-100 hover:bg-red-500 rounded">
                                <i class="fa-solid fa-trash fa-w"></i><span>Delete</span>
                            </button>
                    </div>
                    @endhasrole

                </div>


            </div>
            <!-- Modal for user delete confirmation -->
            <div id="userDeleteConfirm-{{$user->id}}"
                class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
                <div class="bg-white p-6 flex justify-center items-center flex-col rounded drop-shadow-lg">
                    <div>
                        <i class="fa-solid fa-question text-white px-4 py-3 bg-yellow-500 rounded-full drop-shadow-lg"></i>
                    </div>

                    <div class="mt-2">
                        <h1>Are you sure to delete this user?</h1>
                    </div>

                    <div class="space-x-1 mt-3">
                        <button type="submit" class="text-lg hover:underline text-green-300 hover:text-green-500">Yes,
                            proceed.</button>
                        <button type="button"
                            onclick="document.getElementById('userDeleteConfirm-{{$user->id}}').classList.add('hidden')"
                            class="text-lg hover:underline text-red-300 hover:text-red-500">No,
                            cancel.</button>
                    </div>



                </div>
            </div>
            </form>
        @endforeach
    </div>
</div>

<!-- Success pop up message -->
@if(session('success'))
    <div id="success" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white px-6 py-4 rounded">
            <div>
                <h1 class="text-lg">{{ session('success') }}</h1>
            </div>
            <div class="flex justify-end mt-2">
                <button onclick="document.getElementById('success').classList.toggle('hidden')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-500 rounded">OK</button>
            </div>
        </div>
    </div>
@endif

<!-- Modal for adding new user -->
<div id="userAddModal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white px-4 pb-2 w-64 rounded">
        <form action="{{ route('userAdd') }}" method="POST">
            @csrf
            @method('POST')
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium">Add new user</h1>
                <button type="button" onclick="document.getElementById('userAddModal').classList.add('hidden')"
                    class="text-6xl font-thin hover:text-gray-300 focus:outline-none">&times;</button>
            </div>
            <section class="space-y-2">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" placeholder="Input name"
                        class="block p-2 border border border-gray-300 w-full rounded">
                </div>

                <div>
                    <label for="name">Email:</label>
                    <input type="email" name="email" placeholder="Input email"
                        class="block p-2 border border border-gray-300 w-full rounded">
                </div>
                <div class="flex justify-end space-x-1">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">
                        Save
                    </button>
                    <button type="button" class="px-4 py-2 bg-gray-500 text-gray-100 hover:bg-gray-800 rounded">
                        Cancel
                    </button>
                </div>

            </section>
        </form>


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

    function iconEye() {

        const password = document.getElementById('password');
        const type = password.type === 'password' ? 'text' : 'password';

        password.type = type;

        document.getElementById('eye-icon').classList.toggle('fa-eye-slash');
        document.getElementById('eye-icon').classList.toggle('fa-eye');

    }


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