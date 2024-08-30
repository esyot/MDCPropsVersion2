@extends('layouts.header')
@section('content')

<!-- <div class="flex p-4 bg-gray-300 text-white">
    <div class="bg-white p-2 rounded-xl border border-gray-400">
        <i class="text-black fa-solid fa-list"></i>
        <select name="category" class="text-black bg-transparent focus:outline-none">
            @foreach($currentCategory as $category)
                <option class="text-red-500 font-semibold" value="{{ $category->id }}">{{ $category->title }}
                </option>
            @endforeach
            @foreach($categories as $category)
                <option class="text-black" value="{{ $category->id }}">{{ $category->title }}</option>
            @endforeach
        </select>
    </div>

</div> -->
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

    /* Removed hover-based animation to avoid conflicts with JavaScript */
</style>

<form id="category-add-form" method="POST" action="{{ route('category-add') }}" enctype="multipart/form-data">
    @csrf <!-- Add CSRF token for Laravel security -->
    <div id="category-add-modal"
        class="fixed flex inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div id="category-add-content" class="bg-white shadow-md max-w-full rounded">

            <div class="flex justify-between px-1 items-end space-x-6">
                <h1 class="px-2 text-xl font-bold">Add Category</h1>
                <button type="button" onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                    class="text-4xl hover:text-gray-400 font-medium">&times;</button>
            </div>

            <div class="px-4">

                <div class="mt-2">
                    <input type="file" name="files[]" class="file-upload-input" accept="image/*" multiple>
                </div>
                <div class="mt-2">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" title="Title"
                        class="block p-2 border border-gray-300 rounded w-full">
                </div>


                <div class="flex flex-col mt-2">
                    <label for="approval">Can Approved by:</label>
                    <select name="approval_level" id="" class="block p-2 border border-gray-300 rounded">
                        <option value="1"
                            title="Only the administrator can approve items that belong to this category.">
                            Admin only</option>
                        <option value="2" title="Only the staff can approve items that belong to this category.">Staff
                            only</option>
                        <option value="3" title="Both roles can approve items within this category.">Both</option>
                    </select>
                </div>
                <div class="flex justify-end my-2 space-x-1">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-blue-100 hover:bg-blue-800 rounded">Add</button>
                    <button type="button"
                        onclick="document.getElementById('category-add-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-500 text-gray-100 hover:bg-gray-800 rounded">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="relative m-4">
    <div class="flex flex-wrap gap-6">
        <!-- Add Category Button -->
        <div title="Add a new category"
            class="flex flex-col bg-gray-200 rounded-lg hover:bg-gray-300 hover:shadow-inner w-64 h-64 overflow-hidden">
            <div class="flex items-center justify-center cursor-pointer hover:text-gray-800 text-gray-400 "
                onclick="document.getElementById('category-add-modal').classList.remove('hidden')">
                <h1 class="text-8xl mb-3 font-bold py-2 w-50 h-50 object-cover cursor-pointer">+</h1>
            </div>
            <div class="bg-blue-500 w-full h-full shadow-md text-center p-2 flex items-center justify-center">
                <h1 class="text-white py-2 font-bold">Add a new category</h1>
            </div>
        </div>

        <!-- Category Items -->
        @foreach ($categories as $category)
                <div class="flex flex-col text-white rounded-lg w-64 h-64 overflow-hidden">
                    <div class="relative w-full max-w-3xl overflow-hidden slide-container">
                        <div class="slide-wrapper shadow-inner">
                            @php
                                $directory = storage_path('app/public/images/categories/' . $category->folder_name); // Folder where your images are stored
                                $images = array_diff(scandir($directory), array('..', '.'));
                            @endphp

                            @foreach ($images as $image)
                                <div class="slide">
                                    <img src="{{ asset('storage/images/categories/' . $category->folder_name . '/' . $image) }}"
                                        alt="Image">
                                </div>
                            @endforeach
                        </div>
                        <button class="prev-slide" onclick="moveSlide(event, -1)"
                            onmouseover="moveSlide(event, -1);">&#10094;</button>
                        <button class="next-slide" onclick="moveSlide(event, 1)"
                            onmouseover="moveSlide(event, 1);">&#10095;</button>
                    </div>
                    <div class="bg-blue-500 w-full h-full shadow-md text-center p-2 flex items-center justify-center">
                        <h1 class="text-lg font-semibold drop-shadow">{{ $category->title }}</h1>
                    </div>
                </div>
        @endforeach
    </div>
</div>
<script>
    let currentIndex = 0;

    setInterval(moveSlide(event, 1), 100);// Add this line to initialize currentIndex

    function moveSlide(event, step) {
        event.stopPropagation();
        const slideContainer = event.target.closest('.slide-container');
        const slideWrapper = slideContainer.querySelector('.slide-wrapper');
        const slides = slideWrapper.querySelectorAll('.slide');
        const totalSlides = slides.length;

        currentIndex = (currentIndex + step + totalSlides) % totalSlides;
        const offset = -currentIndex * 100;
        slideWrapper.style.transform = `translateX(${offset}%)`;
    }

    function startAutoSlide(slideContainer) {
        const slideWrapper = slideContainer.querySelector('.slide-wrapper');
        const totalSlides = slideWrapper.querySelectorAll('.slide').length;

        // Automatically move slides every 2 seconds
        return setInterval(() => {
            moveSlide({ stopPropagation: () => { } }, 1);
        }, 100);
    }

    function resetAutoSlide(interval, slideContainer) {
        clearInterval(interval);
        return startAutoSlide(slideContainer);
    }

    // Initialize auto-slide for each container and handle hover events
    document.querySelectorAll('.slide-container').forEach(slideContainer => {
        let autoSlideInterval = startAutoSlide(slideContainer);

        // Restart the auto-slide on hover
        slideContainer.addEventListener('mouseenter', () => {
            autoSlideInterval = resetAutoSlide(autoSlideInterval, slideContainer);
        });

        // Stop the auto-slide when not hovering
        slideContainer.addEventListener('mouseleave', () => {
            clearInterval(autoSlideInterval);
        });
    });


</script>

</body>

</html>

@endsection