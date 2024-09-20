@extends('layouts.header')
@section('content')

<!-- Main Container -->
<div id="dashboard" class="flex flex-col h-full">
    @if ($categoriesIsNull == false)
        @include('pages.partials.calendar')
    @else

        @include('pages.partials.errors.category-null-error')

    @endif

</div>




@endsection