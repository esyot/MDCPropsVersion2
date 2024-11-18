@extends('admin.layouts.header')
@section('content')

<div id="dashboard" class="h-full w-full">

    @if ($categoriesIsNull == false)
        @include('admin.partials.calendar')
    @else

        @include('admin.partials.errors.category-null-error')

    @endif

</div>
@endsection