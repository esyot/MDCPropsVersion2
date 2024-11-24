@extends('admin.layouts.header')

@section('content')
<div id="users-header" class="flex justify-between items-center bg-gray-200 p-4 shadow-md z-40">
    <div>
        <a href="{{ route('users') }}" class="flex items-center space-x-1 hover:opacity-50">
            <i class="fas fa-circle-chevron-left text-blue-500"></i>
            <span>
                Back to users
            </span>

        </a>
    </div>

    <div class="flex items-center">
        <form hx-get="{{ route('admin.users-search') }}" hx-trigger="input" hx-target="#users" hx-swap="innerHTML"
            class="flex space-x-1 items-center bg-white p-2 rounded-full shadow-md">
            @csrf
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Search user..." class="focus:outline-none">

        </form>

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


        <div id="requests" class="flex flex-wrap flex-grow gap-2">
            <table class="min-w-full table-auto border-collapse bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 text-left">


                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Requested at</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($passwordResetRequests as $request)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="px-6 py-4 text-sm text-gray-700">{{ $request->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $request->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $request->created_at->format('l, F j, Y \a\t h:i A') }}
                            </td>
                            <td class="space-x-2 justify-between px-6 py-4 text-sm text-gray-700">
                                <button class="hover:opacity-50 cursor-pointer"
                                    onclick="document.getElementById('password-reset-confirm-{{$request->id}}').classList.toggle('hidden')">
                                    <i class="fa-solid fa-share-from-square text-yellow-500"></i>
                                </button>

                                <button class="hover:opacity-50 cursor-pointer"
                                    onclick="document.getElementById('password-reset-form-{{$request->id}}').classList.toggle('hidden')">
                                    <i class="fa-solid fa-pencil text-blue-500"></i>
                                </button>

                            </td>

                        </tr>
                        <div id="password-reset-confirm-{{$request->id}}"
                            class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">
                            <div class="bg-white shadow-md max-w-md rounded w-[500px]">
                                <div class="bg-blue-500 py-1 w-full rounded-t">
                                </div>
                                <div
                                    class="flex space-x-4 p-4 border-b-2 justify-start items-center font-semibold items-start">

                                    <div>
                                        <i class="fa-solid fa-question-circle fa-2xl text-blue-500"></i>
                                    </div>

                                    <div>
                                        <h1 class="text-2xl">
                                            Confirmation
                                        </h1>
                                        <p class="font-normal">Are you sure to confirm password reset?</p>
                                        <small>Note: <i class="font-normal">once submitted, random password will be sent to
                                                the
                                                user.</i></small>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2 p-2 bg-gray-100 rounded-b">
                                    <button type="button"
                                        onclick="document.getElementById('password-reset-confirm-{{$request->id}}').classList.add('hidden')"
                                        class="font-medium px-4 py-2 border border-blue-300 text-blue-500 hover:opacity-50 text-gray-800 rounded">
                                        No, cancel.
                                    </button>
                                    <form
                                        action="{{ route('admin.user-password-request-reset', ['action' => 'random', 'email' => $request->email]) }}"
                                        method="POST">
                                        @csrf

                                        <button type="submit"
                                            class=" font-medium px-4 py-2 bg-blue-500 hover:opacity-50 text-blue-100 rounded">
                                            Yes, sure.
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div id="password-reset-form-{{$request->id}}"
                            class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
                            <div class="bg-white rounded w-[300px]">

                                <header class="flex justify-between items-center  bg-blue-500 rounded-t">
                                    <h1 class="text-white px-2">
                                        Password Reset
                                    </h1>
                                    <button class="text-2xl px-2 text-white hover:opacity-50"
                                        onclick="document.getElementById('password-reset-form-{{$request->id}}').classList.toggle('hidden')">
                                        &times;
                                    </button>

                                </header>
                                <form
                                    action="{{ route('admin.user-password-request-reset', ['action' => 'edit', 'email' => $request->email]) }}"
                                    method="POST">
                                    @csrf
                                    <section class="flex flex-col p-2">
                                        <div class="flex justify-center">
                                            <h1 class="font-medium">Reset password for {{$request->email}}</h1>
                                        </div>
                                        <div class="mt-2">

                                            <label for="">New Password:</label>
                                            <div
                                                class="flex justify-between items-center block p-2 border border-gray-300 rounded w-full">
                                                <input id="new-password" type="password" maxlength="12" minlength="8"
                                                    placeholder="Input Password" class="w-full focus:outline-none">
                                                <i id="toggle-password"
                                                    class="fas fa-eye-slash opacity-50 hover:opacity-100 cursor-pointer"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <label for="">Confirm Password:</label>
                                            <div
                                                class="flex justify-between items-center block p-2 border border-gray-300 rounded w-full">
                                                <input name="password" id="confirm-password" type="password" maxlength="12"
                                                    minlength="8" placeholder="Confirm Password"
                                                    class="w-full focus:outline-none">
                                                <i id="toggle-confirm-password"
                                                    class="fas fa-eye-slash opacity-50 hover:opacity-100 cursor-pointer"></i>
                                            </div>
                                        </div>
                                        <footer class="flex justify-end mt-2">
                                            <button id="submit-btn"
                                                class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 rounded hidden">Submit</button>
                                        </footer>
                                    </section>
                                </form>

                                <script>

                                    document.getElementById('confirm-password').addEventListener('input', matchPassword);

                                    function matchPassword() {
                                        var password1 = document.getElementById('new-password').value;
                                        var password2 = document.getElementById('confirm-password').value;

                                        // If passwords match, show the submit button, otherwise keep it hidden
                                        if (password1 === password2 && password1.length > 0 && password2.length > 0) {
                                            document.getElementById('submit-btn').classList.remove('hidden');
                                        } else {
                                            document.getElementById('submit-btn').classList.add('hidden');
                                        }
                                    }

                                    function togglePasswordVisibility(inputId, iconId) {
                                        var input = document.getElementById(inputId);
                                        var icon = document.getElementById(iconId);

                                        if (input.type === "password") {
                                            input.type = "text";
                                            icon.classList.remove("fa-eye-slash");
                                            icon.classList.add("fa-eye");
                                        } else {
                                            input.type = "password";
                                            icon.classList.remove("fa-eye");
                                            icon.classList.add("fa-eye-slash");
                                        }
                                    }

                                    // Add event listeners to the icons for password visibility toggle
                                    document.getElementById('toggle-password').addEventListener('click', function () {
                                        togglePasswordVisibility('new-password', 'toggle-password');
                                    });

                                    document.getElementById('toggle-confirm-password').addEventListener('click', function () {
                                        togglePasswordVisibility('confirm-password', 'toggle-confirm-password');
                                    });
                                </script>


                            </div>

                        </div>
                    @endforeach

                </tbody>
            </table>


        </div>


    </div>
</div>


@endsection