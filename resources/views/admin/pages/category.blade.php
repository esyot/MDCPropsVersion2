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


@include('admin.modals.category-add')

<script>
    function previewImages(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('w-24', 'h-24', 'object-cover', 'rounded', 'border', 'border-gray-300'); // Add styles for the preview
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    }
</script>

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
            height: 300px;

        }

        #card {
            width: 100%;
            height: 300px;

        }


    }
</style>

<div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
    <div class="flex flex-wrap flex-grow gap-2">
        <!-- Add Category Button -->
        @can('can manage categories')
            <div id="add-new-category-card" title="Add a new category"
                class="flex flex-col bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-52 h-52 overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-90' : '' }}">
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
                    class="flex flex-col text-white rounded-lg overflow-hidden {{ $setting->transition == true ? 'transform transition-transform duration-300 hover:scale-90' : '' }}">
                    <div class="relative w-full overflow-hidden slide-container">
                        <div class="slide-wrapper shadow-md z-50">
                            @php
                                $directory = storage_path('app/public/images/categories/' . $category->folder_name);
                                $images = array_diff(scandir($directory), array('..', '.'));
                            @endphp

                            @foreach ($images as $image)
                                <div class="slide bg-gray-100">
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

                        <div class="w-full space-y-2">
                            <button title="Edit Details"
                                onclick="document.getElementById('category-update-{{$category->id}}').classList.remove('hidden')"
                                class="py-2 w-full shadow-md bg-blue-100 text-blue-800 hover:opacity-50 rounded">Edit
                                Details
                            </button>

                            @hasrole('superadmin|admin')
                            <button title="Assign Users"
                                onclick="document.getElementById('category-{{$category->id}}').classList.remove('hidden')"
                                class="py-2 w-full bg-blue-500 text-blue-100 hover:opacity-50 hover:text-green-100 rounded">Assign
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