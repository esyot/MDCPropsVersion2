@extends('admin.layouts.header')
@section('content')

<style>
    .slide-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    .slide-wrapper {
        display: flex;
        transition: transform 0.5s ease-in-out;
        /* Ensure smooth transition */
    }

    .slide {
        min-width: 100%;
        box-sizing: border-box;
    }

    .slide img {
        width: 100%;
        height: auto;
    }

    .prev-slide,
    .next-slide {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 10;
    }

    .prev-slide {
        left: 0;
    }

    .next-slide {
        right: 0;
    }
</style>


<form id="category-add-form" method="POST" action="{{ route('category-add') }}" enctype="multipart/form-data">
    @csrf
    <div id="category-add-modal"
        class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div id="category-add-content" class="bg-white shadow-md max-w-full rounded">



            <div class="flex justify-between p-2 items-center space-x-6">
                <h1 class="px-2 text-xl font-bold">Add Category</h1>
                <button type="button" onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                    class="text-xl hover:text-gray-400 font-medium">&times;</button>
            </div>


            <div class="bg-gray-100 p-2 mt-2 border-t border-b border-gray-300">

                <div class="mt-2 flex flex-col">
                    <input type="file" name="files[]" class="file-upload-input" accept="image/*" multiple required>
                    <small class="font-bold">Note:
                        <i class="font-normal">Make sure the selected files are in "jpg", "png", or "jpeg"
                            format.</i>
                    </small>
                </div>

                <div class="mt-2">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" title="Title"
                        class="block p-2 border border-gray-300 rounded w-full" required>
                </div>

                <div class="flex flex-col mt-2">
                    <label for="approval">Approval level:</label>
                    <select name="approval_level" id="" class="block p-2 border border-gray-300 rounded" required>
                        <option value="1"
                            title="Only the administrator can approve items that belong to this category.">
                            Admin only</option>
                        <option value="2" title="Only the staff can approve items that belong to this category.">
                            Staff
                            only</option>
                        <option value="3" title="Both roles can approve items within this category.">Both</option>
                    </select>

                </div>
            </div>
            <div class="flex justify-end p-2 space-x-1">
                <button type="submit"
                    class="px-4 py-2 bg-green-100 text-green-800 hover:bg-green-500 hover:text-green-100 shadow-md rounded">Add</button>
                <button type="button" onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                    class="px-4 py-2 bg-red-100 text-red-800 hover:bg-red-500 hover:text-red-100 shadow-md rounded">Close</button>
            </div>


        </div>
    </div>
</form>
<style>
    @media(orientation:landscape) {
        #add-new-category-card {
            width: 300px;
            height: 300px;

        }

        #card {
            width: 300px;
            height: 300px;
        }


    }

    @media(orientation:portrait) {
        #add-new-category-card {
            width: 100%;
            height: 200px;

        }

        #card {
            width: 100%;
            height: 200px;

        }


    }
</style>

<div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
    <div class="flex flex-wrap flex-grow gap-2">
        <!-- Add Category Button -->
        @can('can manage categories')
            <div id="add-new-category-card" title="Add a new category"
                class="flex flex-col bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-110' : '' }}">
                <div class="h-full flex items-center justify-center cursor-pointer hover:text-gray-800 text-gray-400 "
                    onclick="document.getElementById('category-add-modal').classList.remove('hidden')">
                    <h1 class="text-8xl mb-3 font-bold py-2 w-50 h-50 object-cover cursor-pointer">+</h1>
                </div>
                <div
                    class="bg-gradient-to-b p-2 from-blue-500 to-blue-800 w-full h-full shadow-md text-center flex items-center justify-center">
                    <h1 class="text-white py-2 font-bold">Add a new category</h1>
                </div>
            </div>
        @endcan



        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', checkCheckboxes);
                });

                function checkCheckboxes() {
                    const categoryId = this.closest('form').querySelector('input[type="checkbox"]').name.match(/\[(\d+)\]/)[1];
                    const checkboxes = document.querySelectorAll(`#category-${categoryId} .category-checkbox`);
                    const allUnchecked = Array.from(checkboxes).every(checkbox => !checkbox.checked);

                    const input = document.getElementById(`isUncheckedAll-${categoryId}`);
                    input.value = allUnchecked ? "true" : "false";
                }

            });
        </script>


        <!-- Category Items -->
        @foreach ($categories as $category)  

                <div id="card"
                    class="flex flex-col text-white rounded-lg w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-110' : '' }}">
                    <div class="relative w-full max-w-3xl overflow-hidden slide-container ">
                        <div class="slide-wrapper shadow-inner z-50">
                            @php
                                $directory = storage_path('app/public/images/categories/' . $category->folder_name);
                                $images = array_diff(scandir($directory), array('..', '.'));
                            @endphp

                            @foreach ($images as $image)
                                <div class="slide">
                                    <img src="{{ asset('storage/images/categories/' . $category->folder_name . '/' . $image) }}"
                                        alt="Image">
                                </div>
                            @endforeach
                        </div>
                        <button class="prev-slide">&#10094;</button>
                        <button class="next-slide">&#10095;</button>
                    </div>
                    <div
                        class="flex-col bg-gradient-to-b from-blue-500 to-blue-800 w-full h-full shadow-md text-center p-2 flex items-center justify-center">
                        <div class="flex items-center justify-center">
                            <h1 class="text-lg font-semibold drop-shadow">{{ $category->title }}</h1>
                        </div>

                        <div class="space-y-1">
                            <button title="Edit Details"
                                onclick="document.getElementById('category-update-{{$category->id}}').classList.remove('hidden')"
                                class="w-full shadow-md px-4 py-2 bg-blue-100 text-blue-800 hover:bg-blue-800 hover:text-blue-100 rounded">Edit
                                Details
                            </button>

                            @hasrole('superadmin|admin')
                            <button title="Assign Users"
                                onclick="document.getElementById('category-{{$category->id}}').classList.remove('hidden')"
                                class="w-full shadow-md px-4 py-2 bg-green-100 text-green-800 hover:bg-green-500 hover:text-green-100 rounded">Assign
                                Users
                            </button>
                            @endhasrole
                        </div>
                    </div>
                </div>
        @endforeach

    </div>
</div>
@foreach ($categories as $category) 
    @include('admin.modals.category-assign') 
    @include('admin.modals.category-update')
@endforeach
<script>

    document.querySelectorAll('.slide-container').forEach(slideContainer => {
        let currentIndex = 0;

        const slideWrapper = slideContainer.querySelector('.slide-wrapper');
        const slides = slideWrapper.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function moveSlide(step) {
            currentIndex = (currentIndex + step + totalSlides) % totalSlides;
            const offset = -currentIndex * 100;
            slideWrapper.style.transform = `translateX(${offset}%)`;
        }


        slideContainer.querySelector('.prev-slide').addEventListener('click', () => moveSlide(-1));
        slideContainer.querySelector('.next-slide').addEventListener('click', () => moveSlide(1));
    });
</script>



@endsection