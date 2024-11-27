@extends('admin.layouts.header')
@section('content')

<header class="flex p-2 justify-between bg-blue-200">
    <div class="flex space-x-4 p-2 items-center">

        <div>
            <label for="">Rentee:</label>
            <select name="" id="">
                <option value="all">All</option>
                @foreach ($rentees as $rentee)

                    <option value="{{$rentee->id}}">{{$rentee->name}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="">Categories:</label>
            <select name="" id="">
                <option value="all">All</option>
                @foreach ($categories as $category)

                    <option value="{{$category->id}}">{{$category->title}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="">Properties:</label>
            <select name="" id="">
                <option value="all">All</option>
                @foreach ($properties as $property)

                    <option value="{{$property->id}}">{{$property->name}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="">Year:</label>
            <select name="" id="">
                <option value="all">All</option>
            </select>
        </div>
    </div>

    <div>
        <form action="">
            <input type="text" placeholder="Search...">
        </form>
    </div>

</header>

<section class="w-full p-2">
    <table class="min-w-full text-sm text-gray-500">
        <thead class="bg-gray-200 text-gray-700">
            <tr>

                <th class="px-6 py-3 text-left">Rentee</th>
                <th class="px-6 py-3 text-left">Category</th>
                <th class="px-6 py-3 text-left">Property</th>
                <th class="px-6 py-3 text-left">Date Rent Start</th>
                <th class="px-6 py-3 text-left">Date End Start</th>
                <th class="px-6 py-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr class="bg-white border-b hover:bg-gray-100">
                    <td class="px-6 py-3">{{$record->reservation->rentee->name}}</td>
                    <td class="px-6 py-3">{{$record->category->title}}</td>
                    <td class="px-6 py-3">{{$record->property->name}}</td>
                    <td class="px-6 py-3">{{$record->date_start}} {{$record->time_start}}</td>
                    <td class="px-6 py-3">{{$record->date_end}} {{$record->time_end}}</td>
                    <td class="px-6 py-3">pending</td>

                </tr>
            @endforeach

        </tbody>
    </table>

</section>
@endsection