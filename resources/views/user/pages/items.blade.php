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

        #title {
            width: 250px;
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


    <div class="container mx-auto px-4 py-6">
        <a href="{{ route('home') }}" class="hover:opacity-50 fixed z-40 px-4 py-2 rounded">
            <i class="fas fa-arrow-circle-left fa-2xl text-blue-500"></i>
        </a>

        <div id="rightbar" class="flex fixed right-0 justify-end">
            <button title="Messages"
                class="hover:opacity-50 mb-2 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fab fa-facebook-messenger fa-2xl text-blue-400"></i>
            </button>

            <button title="Cart" class="hover:opacity-50 z-40 drop-shadow px-4 py-2 rounded flex flex-col items-center">
                <i class="fas fa-shopping-cart fa-2xl text-blue-400"></i>
            </button>

        </div>

        <div class="flex justify-center text-center">
            <h1 id="title" class="text-2xl font-bold mb-8 flex-wrap">{{ $items->first()->category->title }}
            </h1>
        </div>

        <div class="flex flex-wrap -mx-2 overflow-y-auto">
            @foreach($items as $item)
                <div
                    class="w-full md:w-1/3 px-2 mb-4 transition-transform ease-in-out duration-300 hover:scale-90 hover:opacity-50">
                    <div class="shadow-lg rounded-lg overflow-hidden">
                        <div class="flex h-[200px] relative">
                            <img src="{{ asset('storage/images/categories/' . $item->category->folder_name . '/' . $item->img) }}"
                                alt="Image" class="object-cover w-full h-full absolute inset-0">

                        </div>
                        <div class="text-center bg-blue-500 text-blue-100 p-4 h-24 overflow-hidden">
                            <h2 class="text-lg font-semibold">{{ $item->name }}</h2>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>