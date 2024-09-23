@extends('admin.layouts.header')
@section('content')

<!-- Main Container -->
<div id="dashboard" class="flex flex-col h-full">
    @if ($categoriesIsNull == false)
        @include('admin.pages.partials.calendar')
    @else

        @include('admin.pages.partials.errors.category-null-error')

    @endif

</div>




@endsection