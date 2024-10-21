@extends('admin.layouts.header')

@section('content')
<div id="users-header" class="flex justify-between items-center bg-gray-200 p-4 shadow-md z-40">
    <div class="flex items-center">
        <form hx-get="{{ route('usersFilter') }}" hx-trigger="input" hx-target="#users" hx-swap="innerHTML"
            class="flex space-x-1 items-center bg-white p-2 rounded-full shadow-md">
            @csrf
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Search user..." class="focus:outline-none">

        </form>

    </div>
    <div>
        <button type="button" class="px-4 py-2 bg-blue-500 text-blue-100 rounded hover:opacity-50"
            onclick="document.getElementById('userAddModal').classList.remove('hidden')">
            Add User
        </button>
    </div>
</div>
<style>
    @media(orientation: landscape) {
        #add-new-user-card {
            width: 300px;

        }

        #card {
            width: 300px;
        }
    }

    @media(orientation: portrait) {
        #add-new-user-card {
            width: 100%;
        }

        #card {
            width: 100%;
        }
    }
</style>

<div id="main-content" class="w-full h-[90%] relative p-4 overflow-y-auto custom-scrollbar">
    <div class="flex flex-wrap flex-grow gap-2">

        @hasrole('superadmin')

        <!-- <div id="add-new-user-card" title="Add a new user"
            class="flex flex-col rounded-lg bg-gray-300 {{ $setting->transition == true ? 'transition-transform transform duration-300 hover:scale-90' : '' }}">
            <div class="flex justify-center items-center rounded-lg bg-gray-200 h-3/4 cursor-pointer hover:text-gray-800 text-gray-400 "
                >
                <h1 class="text-8xl mb-3 font-bold py-2 w-50 h-50 object-cover cursor-pointer">+</h1>
            </div>
            <div class="bg-blue-500 w-full h-1/4 shadow-md text-center flex items-center rounded-b-lg justify-center">
                <h1 class="text-lg font-semibold text-white truncate">Add User</h1>
            </div>
        </div> -->


        @endhasrole
        <div id="users" class="flex flex-wrap flex-grow gap-2">
            @include('admin.partials.users')

        </div>


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

@if(session('error'))
    <div id="success" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white px-6 py-4 rounded">
            <div>
                <h1 class="text-lg">{{ session('error') }}</h1>
            </div>
            <div class="flex justify-end mt-2">
                <button onclick="document.getElementById('success').classList.toggle('hidden')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-500 rounded">OK</button>
            </div>
        </div>
    </div>
@endif

@include('admin.modals.user-add')
<!-- Modal -->
<div id="roleModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg shadow-md w-64">

        <div class="p-2 flex justify-between items-start ">
            <div>
                <h2 class="text-xl font-semibold">Edit Role</h2>


            </div>
            <button class="text-xl font-bold hover:opacity-50" onclick="closeModal()">&times;</button>

        </div>


        <form id="roleForm" action="{{ route('roleUpdate') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="user_id">

            <div class="flex flex-col p-2 justify-center border-t border-b border-gary-300 bg-gray-100">
                @foreach ($roles as $role)
                    <label class="flex items-center mb-2">
                        <input type="radio" name="role" value="{{ $role->name }}" class="mr-2">
                        {{ $role->name }}
                    </label>
                @endforeach
            </div>

            <div class="flex justify-end p-2 space-x-1">
                <button type="submit"
                    class="bg-green-100 hover:bg-green-500 text-green-800 hover:text-green-100 px-4 py-2 shadow-md rounded">
                    Save
                </button>
                <button type="button" onclick="closeModal()"
                    class="bg-red-100 hover:bg-red-500 hover:text-red-100 text-red-800 px-4 py-2 shadow-md rounded">Cancel</button>

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