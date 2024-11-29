@extends('admin.layouts.header')
@section('content')
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<header class="flex items-center bg-blue-200">
    <div class="flex items-center">
        <script>





        </script>

        <form id="form-1" onchange="this.submit()" action="{{ route('admin.analytics-custom') }}"
            class="flex space-x-4 p-2 items-center">
            <input type="hidden" id="date_start_form_1" name="date_start">
            <input type="hidden" name="date_end_form_1">

            <!-- Rentee Select -->
            <div class=" w-[200px]">
                <label for="rentee-field" class="block text-sm font-medium text-gray-700">Rentee:</label>
                <select name="rentee" id="rentee-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if ($currentRentee != null)
                        <option value="{{$currentRentee->id}}">{{$currentRentee->name}}</option>
                    @endif
                    <option value="all">All</option>
                    @foreach ($rentees as $rentee)
                        <option value="{{$rentee->id}}">{{$rentee->name}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Select -->
            <div class="w-[200px]">
                <label for="category-field" class="block text-sm font-medium text-gray-700">Categories:</label>
                <select name="category" id="category-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if($selectedCategory)
                        <option value="{{$selectedCategory->id}}">{{$selectedCategory->title}}</option>
                    @endif
                    <option value="all">All</option>
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Property Select -->
            <div class="w-[200px]">
                <label for="property-field" class="block text-sm font-medium text-gray-700">Properties:</label>
                <select name="property" id="property-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    @foreach ($properties as $property)
                        <option value="{{$property->id}}">{{$property->name}}</option>
                    @endforeach
                </select>
            </div>

            <style>
                /* Custom Select2 Styling */
                .select2-container .select2-selection {
                    border-radius: 0.375rem;
                    border: 1px solid #d1d5db;
                    background-color: #fff;
                }


                .select2-container--default .select2-selection--single {
                    height: auto;
                }
            </style>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

            <script>
                $(document).ready(function () {
                    // Initialize Select2 on the select elements
                    $('#rentee-field').select2({
                        placeholder: 'Select a rentee',
                        width: '100%'
                    });

                    $('#category-field').select2({
                        placeholder: 'Select a category',
                        width: '100%'
                    });

                    $('#property-field').select2({
                        placeholder: 'Select a property',
                        width: '100%'
                    });
                });
            </script>
        </form>


        <form id="form-2" action="{{ route('admin.analytics-custom') }}" method="GET">

            <input type="hidden" name="rentee" id="rentee">
            <input type="hidden" name="category" id="category">
            <input type="hidden" name="property" id="property">

            <div class="flex space-x-4">
                <div class="flex flex-col">
                    <label for="date_start_select" class="block text-sm font-medium text-gray-700">Date Start:</label>

                    <div class="flex space-x-2">

                        <div class="flex space-x-2">
                            <select id="date_start_select" name="date_start"
                                onchange="toggleDateInput('date_start_select', 'date_start_input')"
                                class="border rounded">
                                @if ($currentDateStart == 'all')
                                    <option value="all">All</option>
                                @endif

                                <option id="date_start_option" value="custom">Custom</option>
                                @if ($currentDateStart != 'all')
                                    <option value="all">All</option>

                                @endif
                            </select>

                            <div id="date_start_input" class="{{ $currentDateStart == 'all' ? 'hidden' : '' }}">
                                <input type="date" id="date_start_selected" value="{{ $currentDateStart }}"
                                    class="border rounded">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label for="date_end_select" class="block text-sm font-medium text-gray-700">Date End:</label>

                    <div class="flex space-x-2">
                        <select id="date_end_select" name="date_end"
                            onchange="toggleDateInput('date_end_select', 'date_end_input')" class="border rounded">
                            @if ($currentDateEnd == 'all')
                                <option value="all">All</option>

                            @endif

                            <option id="date_end_option" value="custom">Custom</option>
                            @if ($currentDateEnd != 'all')
                                <option value="all">All</option>

                            @endif
                        </select>

                        <div id="date_end_input" class="{{ $currentDateEnd == 'all' ? 'hidden' : '' }}">
                            <input type="date" id="date_end_selected" value="{{ $currentDateEnd }}"
                                class="border rounded">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const form = document.getElementById('form-2');

                form.addEventListener('change', function (event) {

                    if (event.target.value === 'all') {

                        document.getElementById('rentee').value = document.getElementById('rentee-field').value;
                        document.getElementById('category').value = document.getElementById('category-field').value;
                        document.getElementById('property').value = document.getElementById('property-field').value;

                        form.submit();
                    }

                    if (event.target.id === 'date_start_select' || event.target.id === 'date_end_select') {
                        return;

                    }

                    document.getElementById('rentee').value = document.getElementById('rentee-field').value;
                    document.getElementById('category').value = document.getElementById('category-field').value;
                    document.getElementById('property').value = document.getElementById('property-field').value;
                    document.getElementById('date_start_option').value = document.getElementById('date_start_selected').value;
                    document.getElementById('date_end_option').value = document.getElementById('date_end_selected').value;

                    form.submit();
                });



            });
        </script>


        <script>

            function toggleDateInput(selectId, inputDivId) {
                var select = document.getElementById(selectId);
                var dateInputDiv = document.getElementById(inputDivId);

                // If "Custom" option is selected, show the input field, else hide it
                if (select.value === 'custom') {
                    dateInputDiv.classList.remove('hidden');
                } else {
                    dateInputDiv.classList.add('hidden');
                }
            }
        </script>


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