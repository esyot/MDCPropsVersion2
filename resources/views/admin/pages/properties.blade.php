@extends('admin.layouts.header')
@section('content')
@if ($categoriesIsNull == false)

    <div id="items-header" class="p-2 z-30">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">


                <form action="{{ route('admin.properties-filter') }}"
                    class="flex space-x-1 items-center bg-white p-2 rounded shadow-md">
                    @csrf
                    <i class="fa-solid fa-list"></i>
                    <select name="category"
                        class="bg-transparent focus:outline-none p-0.5 w-full overflow-hidden text-ellipsis whitespace-nowrap"
                        onchange="this.form.submit();">

                        <option class="text-red-500 font-semibold" value="{{ $currentCategory->id }}">
                            {{ $currentCategory->title }}
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->title }}
                            </option>

                        @endforeach

                    </select>
                </form>
                @hasrole('superadmin|admin')
                <button title="Add Item" onclick="document.getElementById('property-add-modal').classList.toggle('hidden')"
                    class="px-4 py-2 bg-blue-500 text-blue-100 hover:opacity-50 shadow-md rounded">
                    +
                </button>
                @endhasrole

            </div>

            <div>
                <form hx-get="{{ route('admin.property-page-search', ['category_id' => $currentCategory])}}"
                    hx-target="#properties" hx-swap="innerHTML" hx-trigger="input"
                    class="flex w-[150px] items-center rounded-full bg-white p-2 space-x-1 shadow-inner">

                    <i class="fas fa-magnifying-glass fa-lg"></i>
                    <input type="text" name="search_value" class="bg-transparent focus:outline-none"
                        placeholder="Search items...">
                </form>

            </div>
        </div>
    </div>

    @include('admin.modals.property-add')


    <style>
        @media(orientation: landscape) {
            #add-new-item-card {
                width: 200px;

            }

            #card {
                width: 200px;
            }
        }

        @media(orientation: portrait) {
            #add-new-item-card {
                width: 100%;
            }

            #card {
                width: 100%;
            }
        }
    </style>

    <div id="main-content" class="w-full h-full relative p-4 overflow-y-auto custom-scrollbar">
        <div id="properties" class="flex flex-wrap flex-grow gap-2">
            @include('admin.partials.properties')
        </div>
        @include('admin.modals.property-preview')
    </div>

    @include('admin.partials.success.success-modal')
    <script>
        document.getElementById('img').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('img-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>

@else

    @include('admin.partials.errors.category-null-error')

@endif

@endsection