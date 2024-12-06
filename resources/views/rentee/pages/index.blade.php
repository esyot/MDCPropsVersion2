<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
       <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC - Property Rental System</title>
   <!-- Stylesheets -->
   <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <link rel="icon" href="{{ asset('asset/photos/logo.png') }}" type="image/png">

    <!-- JavaScript Libraries -->
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <script src="{{ asset('asset/js/jsQR.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/logo.png') }}" type="image/png">
    <style>
        @media (orientation: portrait) {
            #rightbar {
                display: none;
            }

            #categories-container{
                display: flex;
                flex-direction: column;
                overflow-y: auto;
                overflow-x: hidden;
                gap: 0.5rem;
            }
        }

        @media (orientation: landscape) {
            #categories-container{
                display: flex;
                gap: 0.5rem;
            }

        }
    </style>
</head>

<body class="bg-white overflow-x-hidden overflow-y-auto">
    @include('rentee.modals.information-form')

    @if(session()->has('cart'))
        <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-red-500 text-white rounded-md shadow-md p-6 w-1/3">
                <h2 class="text-xl font-semibold mb-4">Error!</h2>
                <p>{{ session('cart') }}</p>
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
        <div class="bg-white rounded-lg w-[500px] mx-2">
            <div class="bg-red-500 rounded-t py-1">

            </div>

            <div class="flex items-center p-4 space-x-2 border-b-2">
                <div>
                    <i class="fas fa-exclamation-circle fa-2xl text-red-500 "></i>
                </div>
                <div>
                    <h1 class="text-2xl font-medium">Cancel Reservation</h1>

                    <p>This will erase your cart items and start a new
                        transaction.</p>

                </div>

            </div>

            <div class="flex justify-end space-x-1 p-2 bg-gray-100 rounded-b">

                <button onclick="document.getElementById('confirmLogoutModal').classList.add('hidden')"
                    class="px-4 py-2 border border-red-300 rounded text-red-500 hover:opacity-50">No, cancel.</button>
                <a href="{{ route('cancelOrder', ['rentee' => $rentee]) }}"
                    class="px-4 py-2 bg-red-500 rounded text-red-100 hover:opacity-50">Yes, proceed.</a>
            </div>
        </div>
    </div>

    <script>
        function confirmLogoutModal() {
            document.getElementById('confirmLogoutModal').classList.remove('hidden');
        }
    </script>

    <div class="container px-4 py-6">
       
        <div id="rightbar" class="fixed right-0 z-50">
            <div class="flex flex-col items-center space-y-2">


                <a id="cart-icon" @if($cartedProperties != 0 ) href="{{ route('cart', ['rentee' => $rentee]) }}" @endif title="Cart"
                    class="px-2 py-3 bg-blue-400 rounded-full cursor-pointer hover:opacity-50 z-40 drop-shadow px-3 py-2 rounded mr-2">
             @if($cartedProperties != 0)
                    <span class="absolute top-0 right-1 bg-red-500 text-white rounded-full px-[5px] text-xs">
                        {{ $cartedProperties }}
                    </span>
                    @endif
                  
                    <i class="fas fa-shopping-cart fa-xl text-white"></i>


                </a>

                <button onclick="confirmLogoutModal()" title="Cancel Reservation"
                    class="hover:opacity-50 mb-2 mr-2 z-40 drop-shadow px-3 py-3 bg-blue-400 rounded-full">
                    <i class="fa-solid fa-right-from-bracket fa-xl text-white"></i>
                </button>

            </div>

        </div>

        <div>
            <h1 class="text-2xl font-bold text-center mb-2">Categories</h1>
        </div>

        <div id="categories-container" class="flex">
            @foreach($categories as $category)
                        <a onmouseout="document.getElementById('open-{{$category->id}}').classList.add('hidden')"
                            onmouseover="document.getElementById('open-{{$category->id}}').classList.remove('hidden')"
                            href="{{ route('rentee.properties', ['category_id' => $category->id, 'rentee' => $rentee]) }}"
                            title="{{ $category->title }}"
                            class="w-full md:w-1/3 transition-transform ease-in-out duration-300 hover:scale-90">
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <div class="flex h-[200px] relative">
                                    <div id="open-{{$category->id}}"
                                        class="flex relative w-full justify-center transition-transform ease-in-out duration-300 items-center bg-gray-800 bg-opacity-50 hidden z-40">
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




    </script>
</body>

</html>