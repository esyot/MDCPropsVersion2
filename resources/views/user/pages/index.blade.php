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
</head>

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

<body class="bg-white overflow-x-hidden overflow-y-auto">
    @include('user.modals.information-form')

    <div id="sidebar"
        class="fixed left-0 top-0 h-full bg-gradient-to-b from-blue-500 to-blue-800 fromshadow-md transform -translate-x-full transition-transform duration-300 z-50">
        <div class="p-4 text-white">
            <div class="mt-2">
                <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full');">
                    <i class="fas fa-arrow-circle-left fa-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <button id="toggleButton" class="hover:opacity-50 fixed z-40 bg-blue-500 text-white px-4 py-2 rounded">
            <i class="fas fa-bars"></i>
        </button>

        <div id="rightbar" class="flex fixed right-0 justify-end">
            <button title="Messages"
                class="hover:opacity-50 mb-2 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
            </button>

            <button title="Cart" class="hover:opacity-50 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>
            </button>

        </div>




        <h1 class="text-3xl font-bold mb-8 text-center">Categories</h1>
        <div class="flex flex-wrap -mx-2 overflow-y-auto">
            @foreach($categories as $category)
                        <a href="{{ route('userItems', ['category_id' => $category->id]) }}" title="{{ $category->title }}"
                            class="w-full md:w-1/3 px-2 mb-4 transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50">
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <div class="flex h-[200px] relative">
                                    @php
                                        $directory = storage_path('app/public/images/categories/' . $category->folder_name);
                                        $images = array_diff(scandir($directory), array('..', '.'));
                                        $images = collect($images);
                                    @endphp

                                    @if ($images->isNotEmpty())
                                        <img src="{{ asset('storage/images/categories/' . $category->folder_name . '/' . $images->first()) }}"
                                            alt="Image" class="object-cover w-full h-full absolute inset-0">
                                    @endif
                                </div>
                                <div class="text-center bg-blue-500 text-blue-100 p-4 h-24 overflow-hidden">
                                    <h2 class="text-lg font-semibold">{{ $category->title }}</h2>
                                </div>
                                <div>

                                </div>
                            </div>
                            </>
            @endforeach
        </div>
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