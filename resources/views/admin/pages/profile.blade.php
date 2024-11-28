@extends('admin.layouts.header')

@section('content')

@if (session('errors'))
    <div id="error" class="flex fixed inset-0 bg-gray-800 justify-center items-center bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded">
            <div class="flex justify-center">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
            </div>
            <div class="space-y-4">
                @foreach (session('errors')->all() as $error)
                    <h1>{{ $error }}</h1>
                @endforeach
                <div class="flex justify-center">
                    <button class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-500"
                        onclick="document.getElementById('error').classList.add('hidden')">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(!Auth::user()->isPasswordChanged)
    <div id="isPasswordChangedModal" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded">
            <div class="space-y-2">
                <h1>You need to change your password first.</h1>
                <div class="flex justify-end">
                    <button type="button"
                        onclick="document.getElementById('isPasswordChangedModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-500 rounded">OK</button>
                </div>
            </div>

        </div>
    </div>

@endif

@if (session('success'))
    <div id="success" class="flex fixed inset-0 bg-gray-800 justify-center items-center bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded">
            <div class="flex justify-center">
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
            </div>
            <div class="space-y-4">
                <h1>{{ session('success') }}</h1>
                <div class="flex justify-center">
                    <button class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-500"
                        onclick="document.getElementById('success').classList.add('hidden')">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif


<div class="flex justify-center h-full bg-gray-100 pt-4 ">
    <div class="bg-white w-full mx-4 flex p-6 rounded-lg shadow-lg overflow-y-auto">
        <!-- Sidebar -->
        <nav class="w-1/4 border-r border-gray-300 pr-4">
            <h2 class="text-lg font-semibold mb-4">Account Details</h2>
            <ul>
                <li title="Profile" onclick="toggleForm()"
                    class="rounded-xl p-3 hover:bg-gray-200 cursor-pointer flex items-center space-x-2">
                    <i class="fa-solid fa-user text-gray-600"></i>
                    <span>Profile</span>
                </li>

                <script>

                    function toggleForm() {
                        document.getElementById('form-1').classList.remove('hidden');
                        document.getElementById('form-2').classList.add('hidden');
                    }

                </script>
            </ul>
            <h2 class="text-lg font-semibold mt-6 mb-4">Account Settings</h2>
            <ul>
                <li title="Password & Security" onclick="
                document.getElementById('form-1').classList.add('hidden');
                document.getElementById('form-2').classList.remove('hidden');
                
                " class="rounded-xl p-3 hover:bg-gray-200 cursor-pointer flex items-center space-x-2">
                    <i class="fa-solid fa-shield text-gray-600"></i>
                    <span>Password & Security</span>
                </li>
            </ul>
        </nav>

        <!-- Profile and Settings Form -->
        <div class="w-3/4 pl-6">
            <form id="form-1" action="{{ route('profileUpdate', ['id' => Auth::user()->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <h1 class="text-xl font-medium">Profile</h1>

                <div class="mt-2 flex flex-col items-center space-y-4 mb-6">
                    <img id="profile-image" class="bg-gray-100 p-2 w-[100px] h-[100px] rounded-full"
                        src="{{ asset('storage/images/users/' . Auth::user()->img) }}" alt="Profile Image">

                    <input type="file" name="img" accept="image/*" id="img"
                        class="block p-2 border border-gray-300 bg-white rounded">
                </div>

                <script>
                    const fileInput = document.getElementById('img');
                    const profileImage = document.getElementById('profile-image');


                    window.onload = function () {
                        profileImage.src = "{{ asset('storage/images/users/' . Auth::user()->img) }}";
                    };

                    fileInput.addEventListener('change', function () {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                profileImage.src = event.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                </script>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="name" name="name" title="Name"
                        class="block w-full p-2 border border-gray-300 rounded mt-1" value="{{ Auth::user()->name }}"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" title="Email"
                        class="block w-full p-2 border border-gray-300 rounded mt-1" value="{{ Auth::user()->email }}"
                        required>
                </div>

                <div id="profileUpdateConfirm"
                    class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
                    <div class="bg-white px-2 pb-2 rounded">
                        <div class="flex justify-end">
                            <button type="button" onclick="profileUpdateCancel()" class="text-4xl">&times;</button>
                        </div>
                        <div class="flex justify-center">
                            <i
                                class="bg-orange-500 text-orange-100 px-[13px] py-[10px] rounded-full fa-solid fa-question"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-center">
                                <h1 class="text-lg font-medium">Are you sure to update this:</h1>
                            </div>
                            <div class="flex justify-center">
                                <small id="profileUpdateDetails"></small>
                            </div>
                            <div class="flex justify-center space-x-2">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-100 text-green-800 hover:bg-green-500 rounded">Yes,
                                    sure.</button>
                                <button type="button" onclick="profileUpdateCancel()"
                                    class="px-4 py-2 bg-gray-100 text-gray-800 hover:bg-gray-500 rounded">No,
                                    cancel.</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-start">
                    <button type="button" onclick="profileUpdateConfirm()"
                        class="px-4 py-2 bg-green-500 text-white hover:bg-green-600 rounded">Update</button>
                </div>
            </form>


            <style>
                .readonly {
                    cursor: not-allowed;
                    pointer-events: none;

                }
            </style>

            <form id="form-2" action="{{ route('passwordUpdate', ['id' => Auth::user()->id]) }}" method="POST"
                class="hidden">
                @csrf

                <h1 class="text-xl font-medium mb-4">Password & Security</h1>
                @if(Auth::user()->isPasswordChanged)
                    <div class="relative mx-4 mb-4">
                        <input id="password" name="password" type="password" placeholder="Input old password"
                            class="border border-gray-300 w-full rounded-md py-2 pl-12 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                            required>
                        <i
                            class="border border-transparent absolute left-0 top-1/2 transform -translate-y-1/2 text-gray-500 rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-lock"></i>
                        <i id="togglePassword"
                            class="hover:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 cursor-pointer fas fa-eye-slash"></i>
                    </div>
                    <script>
                        document.getElementById('togglePassword').addEventListener('click', function () {
                            const passwordField = document.getElementById('password');
                            const type = passwordField.type === 'password' ? 'text' : 'password';
                            passwordField.type = type;

                            this.classList.toggle('fa-eye-slash');
                            this.classList.toggle('fa-eye');
                        });
                    </script>

                @endif
                <div class="relative mx-4 mb-4">
                    <input id="password1" name="new_password" type="password" placeholder="Input password"
                        class="border border-gray-300 w-full rounded-md py-2 pl-12 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                        required>
                    <i
                        class="border border-transparent absolute left-0 top-1/2 transform -translate-y-1/2 text-gray-500 rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-lock"></i>
                    <i id="togglePassword1"
                        class="hover:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 cursor-pointer fas fa-eye-slash"></i>
                </div>

                <p id="errorMessage1" class="text-red-500 mb-4"></p>

                <div class="relative mx-4 mb-4">
                    <input id="password2" name="confirm_password" type="password" placeholder="Re-type password"
                        class="readonly border border-gray-300 w-full rounded-md py-2 pl-12 outline-none transition-all duration-300 focus:border-dodgerBlue focus:ring-0 focus:ring-dodgerBlue focus:ring-opacity-50"
                        required>
                    <i
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 text-gray-500 rounded-l-md py-3 px-3 transition-colors duration-300 fas fa-lock"></i>
                    <i id="togglePassword2"
                        class="hover:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 cursor-pointer fas fa-eye-slash"></i>
                </div>

                <p id="errorMessage2" class="text-red-500 mb-4"></p>

                <div class="flex justify-start mx-4">
                    <button id="submitButton" type="submit"
                        class="px-4 py-2 bg-green-500 text-white hover:bg-green-600 rounded hidden">Update
                    </button>
                </div>
            </form>


            <script>

                document.getElementById('togglePassword1').addEventListener('click', function () {
                    const passwordField = document.getElementById('password1');
                    const type = passwordField.type === 'password' ? 'text' : 'password';
                    passwordField.type = type;

                    this.classList.toggle('fa-eye-slash');
                    this.classList.toggle('fa-eye');
                });

                document.getElementById('togglePassword2').addEventListener('click', function () {
                    const passwordField = document.getElementById('password2');
                    const type = passwordField.type === 'password' ? 'text' : 'password';
                    passwordField.type = type;

                    this.classList.toggle('fa-eye-slash');
                    this.classList.toggle('fa-eye');
                });

                document.getElementById('form-2').addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
                // Select the password fields and the output paragraphs

                const password1 = document.getElementById('password1');
                const password2 = document.getElementById('password2');
                const errorMessage = document.getElementById('errorMessage');
                const errorMessage1 = document.getElementById('errorMessage1');
                const errorMessage2 = document.getElementById('errorMessage2');
                const submitButton = document.getElementById('submitButton');



                function handleInput1() {

                    errorMessage1.textContent = ''; // Clear the error message if passwords match
                    // Check the length of the first password field
                    if (password1.value.length < 8) {
                        errorMessage1.textContent = 'Password must contain 8-12 characters';

                        password1.classList.remove('border-gray-300');
                        password1.classList.add('border-red-300');

                        submitButton.classList.add('hidden');


                    } else {
                        errorMessage2.textContent = ''; // Clear the error message if password length is valid
                        submitButton.classList.add('hidden');
                        password2.classList.remove('readonly');
                        password1.classList.add('border-gray-300');
                        password1.classList.remove('border-red-300');
                    }

                }

                function handleInput2() {
                    if (password1.value !== password2.value) {
                        errorMessage2.textContent = 'Passwords do not match';
                        submitButton.classList.add('hidden');
                    } else {
                        errorMessage2.textContent = ''; // Clear the error message if passwords match
                        // Check the length of the first password field
                        if (password1.value.length < 8) {
                            errorMessage2.textContent = 'Password must contain at least 8-12 characters';
                            submitButton.classList.add('hidden');
                        } else {
                            errorMessage2.textContent = ''; // Clear the error message if password length is valid
                            submitButton.classList.remove('hidden');
                        }
                    }
                }

                // Add event listeners for the input event on both password fields


                password1.addEventListener('input', handleInput1);
                password2.addEventListener('input', handleInput2);
            </script>


        </div>

    </div>
</div>
<script>
    document.getElementById('form-1').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
    function profileUpdateConfirm() {
        var oldUserName = @json(Auth::user()->name);
        var oldUserEmail = @json(Auth::user()->email);

        var newUserName = document.getElementById('name').value;
        var newUserEmail = document.getElementById('email').value;

        // Display the confirmation element and update its innerHTML
        document.getElementById('profileUpdateConfirm').classList.remove('hidden');
        document.getElementById('profileUpdateDetails').innerHTML = `
                                            <small class='flex justify-center'>
                                                <b>${oldUserName}</b> &nbsp;to&nbsp; <b>${newUserName}</b>,&nbsp; and&nbsp;<br>
                                        </small>
                                        <small>
                                                <b>${oldUserEmail}</b> &nbsp;to&nbsp; <b>${newUserEmail}</b>
                                            </small>?
                                        `;
    }


    function profileUpdateCancel() {

        var userName = @json(Auth::user()->name);
        var userEmail = @json(Auth::user()->email);


        document.getElementById('name').value = userName;
        document.getElementById('email').value = userEmail;


        document.getElementById('profileUpdateConfirm').classList.add('hidden');
    }
</script>
@endsection