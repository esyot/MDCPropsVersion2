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

            #title {
                width: 250px;
                font-size: 1rem;
                line-height: 1.2rem;
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
    <div class="flex items-center justify-between z-50 bg-gradient-to-r from-blue-500 to-blue-800 p-2 shadow-md">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="hover:opacity-50 z-40 px-4 py-2 rounded">
                <i class="fas fa-arrow-circle-left fa-2xl text-white"></i>
            </a>

            <h1 class="text-white font-bold line-clamp-1">{{ $items->first()->category->title }}</h1>

        </div>

        <div class="relative">
            <div id="searchBar" onclick="expand()"
                class="flex space-x-1 items-center bg-white p-2 border border-gray-300 rounded-full">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search Items..."
                    class="w-[100px] bg-transparent focus:outline-none hidden">
            </div>
        </div>

        <script>
            function expand() {
                const searchBar = document.getElementById('searchBar');
                searchBar.classList.add('w-[140px]');
                const input = searchBar.querySelector('input');
                input.classList.remove('hidden');
                input.focus();

            }
        </script>


    </div>

    <div id="rightbar" class="flex fixed right-0 top-[80px] justify-end z-50">
        <button title="Messages" class="hover:opacity-50 mb-2 drop-shadow px-4 py-2 rounded flex flex-col items-center">
            <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
        </button>
        <button title="Cart" class="hover:opacity-50 drop-shadow px-4 py-2 rounded flex flex-col items-center">
            <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>
        </button>
    </div>
    <div class="mx-auto px-4 py-6 relative">



        <div class="flex flex-wrap -mx-2 justify-start">
            @foreach($items as $item)
                <div
                    class="flex flex-col justify-between h-full w-1/3 sm:w-1/4 md:w-1/5 lg:w-1/6 px-2 mt-4 transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50">
                    <div class="shadow-lg rounded-lg overflow-hidden relative">
                        <div class="w-full h-0 pt-[50%] relative">
                            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                alt="Image" class="absolute top-0 left-0 w-full h-full object-contain z-0">
                        </div>
                        <div class="bg-blue-500 text-blue-100 p-2 flex flex-col justify-center text-center relative z-10"
                            style="height: 60px;">
                            <h2 class="font-semibold text-[calc(1.5rem + 1vw)] leading-[1] max-w-full break-words">
                                {{ $item->name }}
                            </h2>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</body>

</html>