<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <title>Dashboard</title>
    <style>
        @media (orientation: portrait) {
            #rightbar {
                flex-direction: column;
                align-items: end;
                justify-content: flex-end;
            }

            #rightbar button {
                margin-top: 10px;
                margin-bottom: 30px;
            }

            #toggleButton {
                display: none;
            }
        }

        @media (orientation: landscape) {
            #rightbar {
                flex-direction: row;
                align-items: start;
                justify-content: flex-end;
            }

            #rightbar button {
                margin-top: 10px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body class="bg-white overflow-x-hidden overflow-y-auto">
    @include('rentee.modals.information-form')

    @if(session()->has('error'))
        <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-red-500 text-white rounded-md shadow-md p-6 w-1/3">
                <h2 class="text-xl font-semibold mb-4">Success!</h2>
                <p>{{ session('error') }}</p>
                <div class="flex justify-end mt-4">
                    <button onclick="document.getElementById('errorModal').classList.add('hidden')"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif


    <div id="confirmLogoutModal"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white p-4 rounded">
            <h1 class="text-2xl">Are you sure to cancel your order?</h1>
            <div class="flex justify-end space-x-1 mt-4">

                <button onclick="document.getElementById('confirmLogoutModal').classList.add('hidden')"
                    class="px-4 py-2 bg-red-500 rounded text-red-100 hover:bg-red-800">No, cancel.</button>
                <a href="{{ route('cancelOrder', ['rentee' => $rentee]) }}"
                    class="px-4 py-2 bg-blue-500 rounded text-blue-100 hover:bg-blue-800">Yes, sure.</a>
            </div>
        </div>
    </div>

    <div id="sidebar"
        class="fixed left-0 top-0 h-full bg-gradient-to-b from-blue-500 to-blue-800 shadow-md transform -translate-x-full transition-transform duration-300 z-50">
        <div class="flex flex-col justify-between h-full p-4 text-white">
            <div class="mt-2">
                <button onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <i class="fas fa-arrow-circle-left fa-lg"></i>
                </button>
            </div>

            <div class="mt-auto">
                <button onclick="confirmLogoutModal()" title="Log-out">
                    <i class="fa-solid fa-right-from-bracket fa-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        function confirmLogoutModal() {
            document.getElementById('confirmLogoutModal').classList.remove('hidden');
        }
    </script>

    <div class="container mx-auto px-4 py-6">
        <button id="toggleButton" class="hover:opacity-50 fixed z-40 bg-blue-500 text-white px-4 py-2 rounded"
            aria-label="Open Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div id="rightbar" class="flex fixed right-0 justify-end">

            <button title="Messages"
                class="hover:opacity-50 mb-2 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
            </button>

            <a href="{{ route('cart', ['rentee' => $rentee]) }}">
                <button title="Cart"
                    class="hover:opacity-50 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                    <span class="absolute bottom-4 right-1 bg-red-500 text-white rounded-full px-[5px] text-xs">
                        {{ $items }}
                    </span>
                    <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>

                </button>
            </a>

        </div>

        <div>
            <h1 class="text-2xl font-bold text-center mb-2">Categories</h1>
        </div>

        <div class="flex flex-wrap -mx-2 overflow-y-auto">
            @foreach($categories as $category)
                        <a onmouseout="document.getElementById('open-{{$category->id}}').classList.add('hidden')"
                            onmouseover="document.getElementById('open-{{$category->id}}').classList.remove('hidden')"
                            href="{{ route('userItems', ['category_id' => $category->id, 'rentee' => $rentee]) }}"
                            title="{{ $category->title }}"
                            class="w-full md:w-1/3 px-2 mb-4 transition-transform ease-in-out duration-300 hover:scale-90">
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <div class="flex h-[200px] relative">
                                    <div id="open-{{$category->id}}"
                                        class="flex relative w-full justify-center transition-transform ease-in-out duration-300 items-center bg-gray-800 bg-opacity-50 hidden z-50">
                                        <div class="px-4 text-blue-100 bg-blue-500 py-2 rounded-full shadow-md hover:bg-blue-800">
                                            Open
                                        </div>
                                    </div>

                                    @php
                                        $directory = storage_path('app/public/images/categories/' . $category->folder_name);
                                        $images = array_diff(scandir($directory), array('..', '.'));
                                        $images = collect($images);
                                    @endphp

                                    @if ($images->isNotEmpty())
                                        <img src="{{ asset('storage/images/categories/' . $category->folder_name . '/' . $images->first()) }}"
                                            alt="{{ $category->title }} Image" class="object-cover w-full h-full absolute inset-0"
                                            loading="lazy">
                                    @else
                                        <div class="flex items-center justify-center w-full h-full bg-gray-200">
                                            <span>No image available</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center bg-blue-500 text-blue-100 p-4 h-24 overflow-hidden">
                                    <h2 class="text-lg font-semibold">{{ $category->title }}</h2>
                                </div>
                            </div>
                        </a>
            @endforeach
        </div>

        @include('rentee.components.footer')
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const button = document.getElementById('toggleButton');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
        }

        button.addEventListener('click', toggleSidebar);

        document.addEventListener('click', (event) => {
            const isClickInside = sidebar.contains(event.target) || button.contains(event.target);
            if (!isClickInside) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>