@extends('admin.layouts.header')
@section('content')

<link href="{{ asset('asset/dist/css/select2.min.css') }}" rel="stylesheet" />

<script src="{{ asset('asset/dist/js/jquery-3.min.js') }}"></script>

<script src="{{ asset('asset/dist/js/select2.min.js') }}"></script>

<style>
    .select2-container .select2-selection {
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: #fff;
    }


    .select2-container--default .select2-selection--single {
        height: auto;
    }
</style>

<script>
    $(document).ready(function () {
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

<header id="analytics-header" class="flex items-center bg-blue-200">
    <div class="flex items-center">
        <form id="form-1" onchange="this.submit()" action="{{ route('admin.analytics-custom') }}"
            class="flex space-x-4 p-2 items-center">

            <input type="hidden" id="date_start_form_1" value="{{ $currentDateStart }}" name="date_start">
            <input type="hidden" id="date_end_form_1" value="{{ $currentDateEnd }}" name="date_end">


            <div class=" w-[200px]">
                <label for="rentee-field" class="block text-sm font-medium ">Rentee:</label>
                <select name="rentee" id="rentee-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700  focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if ($currentRentee != null)
                        <option value="{{$currentRentee->id}}">{{$currentRentee->name}}</option>
                    @endif
                    <option value="all">All</option>
                    @foreach ($rentees as $rentee)
                        <option value="{{$rentee->id}}">{{$rentee->name}}</option>
                    @endforeach
                </select>
            </div>


            <div class="w-[200px]">
                <label for="category-field" class="block text-sm font-medium ">Categories:</label>
                <select name="category" id="category-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700  focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if($selectedCategory)
                        <option value="{{$selectedCategory->id}}">{{$selectedCategory->title}}</option>
                    @endif
                    <option value="all">All</option>
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-[200px]">
                <label for="property-field" class="block text-sm font-medium ">Properties:</label>
                <select name="property" id="property-field"
                    class="block w-full py-2 px-4 border border-gray-300 rounded-md text-gray-700  focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if($selectedProperty)
                        <option value="{{$selectedProperty->id}}">{{$selectedProperty->name}}</option>
                    @endif
                    <option value="all">All</option>

                    @foreach ($properties as $property)
                        <option value="{{$property->id}}">{{$property->name}}</option>
                    @endforeach
                </select>
            </div>


        </form>

        <form id="form-2" action="{{ route('admin.analytics-custom') }}" method="GET">


            <input type="hidden" name="rentee" id="rentee">
            <input type="hidden" name="category" id="category">
            <input type="hidden" name="property" id="property">

            <div class="flex space-x-4">
                <div class="flex flex-col">
                    <label for="date_start_select" class="block text-sm font-medium ">Date Start:</label>

                    <div class="flex space-x-2">

                        <div class="flex space-x-2">
                            <select id="date_start_select" name="date_start"
                                onchange="toggleDateInput('date_start_select', 'date_start_input')"
                                class="border rounded text-gray-700">
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
                    <label for="date_end_select" class="block text-sm font-medium ">Date End:</label>

                    <div class="flex space-x-2">
                        <select id="date_end_select" name="date_end"
                            onchange="toggleDateInput('date_end_select', 'date_end_input')"
                            class="border text-gray-700 rounded">
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

            function toggleDateInput(selectId, inputDivId) {
                var select = document.getElementById(selectId);
                var dateInputDiv = document.getElementById(inputDivId);

                if (select.value === 'custom') {
                    dateInputDiv.classList.remove('hidden');
                } else {
                    dateInputDiv.classList.add('hidden');
                }
            }

        </script>

    </div>

    <form id="exportPdfForm" action="{{ route('admin.analytics-custom-export-to-pdf') }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="action">
        <input type="hidden" name="barChartImageInput" id="barChartImageInput" />
        <input type="hidden" name="pieChartImageInput" id="pieChartImageInput" />
        <input type="hidden" name="category" value="{{ $selectedCategory ? $selectedCategory->id : 'all' }}" />
        <input type="hidden" name="property" value="{{ $selectedProperty ? $selectedProperty->id : 'all' }}" />
        <input type="hidden" name="rentee" value="{{ $currentRentee != null ? $currentRentee->id : 'all' }}" />
        <input type="hidden" name="date_start" value="{{ $currentDateStart}}" />
        <input type="hidden" name="date_end" value="{{ $currentDateEnd}}" />
    </form>

    <div class="relative flex justify-end w-full">
        <button id="options" class="px-4 hover:opacity-50 focus:outline-none">
            <i class="fas fa-ellipsis-vertical"></i>
        </button>

        <div id="optionsMenu"
            class="absolute mx-4 hidden bg-white border border-gray-200 rounded-md shadow-md mt-2 z-50 w-48">
            <ul class="">
                <li onclick="triggerSubmitForm('view')"
                    class=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                    View as .pdf
                </li>
                <li onclick="triggerSubmitForm('download')"
                    class=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Download as .pdf
                </li>

            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const $optionsButton = $('#options');
            const $optionsMenu = $('#optionsMenu');


            $optionsButton.on('click', function (event) {
                $optionsMenu.toggleClass('hidden');
                event.stopPropagation();
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest($optionsButton).length && !$(event.target).closest($optionsMenu).length) {
                    $optionsMenu.addClass('hidden');
                }
            });
        });
    </script>



</header>

<section class="w-full">

    <div id="analytics-charts" class="flex justify-between space-x-2 p-2">

        <div id="transactionChartContainer"
            class="flex flex-col items-center w-full h-96 bg-gray-100 rounded {{ $setting->darkMode ? 'bg-gray-300' : 'bg-white border shadow-md'}}">
            <div class="flex items-center w-full justify-between">

                <div class="flex items-center space-x-1">

                </div>
            </div>
            <div class="relative flex justify-end w-full fixed float-right">
                <button onclick="document.getElementById('transactionChartContainer').classList.add('hidden')"
                    class="px-2 fixed text-2xl font-bold hover:opacity-50">&times;</button>
            </div>

            <canvas id="transactionChart" class="w-full h-full"></canvas>
        </div>

        <div id="myPieChartContainer"
            class="flex flex-col items-center w-full h-96 bg-gray-100 rounded {{ $setting->darkMode ? 'bg-gray-300' : 'bg-white border shadow-md'}}">
            <div class="relative flex justify-end w-full fixed float-right">
                <button onclick="document.getElementById('myPieChartContainer').classList.add('hidden')"
                    class="px-2 fixed text-2xl font-bold hover:opacity-50">&times;</button>
            </div>
            <div class="relative">
                <canvas id="myPieChart"></canvas>
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-square text-yellow-500"></i>
                    <small> {{ $propertiesCanceledCount }} </small>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-square text-red-500"></i>
                    <small> {{ $propertiesDeclinedCount }} </small>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-square text-green-500"></i>
                    <small> {{ $propertiesCompletedCount }} </small>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/js/chart.min.js') }}"></script>

    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label: 'Canceled',
                        data: [
                            @foreach ($canceledCounts as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(255, 193, 7, 0.6)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Declined',
                        data: [
                            @foreach ($declinedCounts as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(244, 67, 54, 0.6)',
                        borderColor: 'rgba(244, 67, 54, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed',
                        data: [
                            @foreach ($completedCounts as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(76, 175, 80, 0.6)',
                        borderColor: 'rgba(76, 175, 80, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,  // Disable aspect ratio to manually set width and height
                layout: {
                    padding: {
                        top: 20,    // Padding from top
                        right: 20,  // Padding from right
                        bottom: 20, // Padding from bottom
                        left: 20    // Padding from left
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        stacked: false
                    }
                }
            }
        });

        const data = {
            labels: ['Canceled', 'Declined', 'Completed'],
            datasets: [{
                label: 'My First Dataset',
                data: [{{$propertiesCanceledCount}}, {{$propertiesDeclinedCount}}, {{$propertiesCompletedCount}}],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.6)',
                    'rgba(244, 67, 54, 0.6)',
                    'rgba(76, 175, 80, 0.6)'
                ],
                hoverOffset: 4
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,  // Disable aspect ratio to manually set width and height
            layout: {
                padding: {
                    top: 20,    // Padding from top
                    right: 20,  // Padding from right
                    bottom: 20, // Padding from bottom
                    left: 20    // Padding from left
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        };

        const asd = document.getElementById('myPieChart').getContext('2d');
        const myPieChart = new Chart(asd, {
            type: 'pie',
            data: data,
            options: options
        });



        function triggerSubmitForm(action) {
            const transactionCanvas = document.getElementById('transactionChart');
            const pieChartCanvas = document.getElementById('myPieChart');

            if (transactionCanvas && pieChartCanvas) {

                if (transactionCanvas.width > 0 && transactionCanvas.height > 0 &&
                    pieChartCanvas.width > 0 && pieChartCanvas.height > 0) {


                    const barChartImage = transactionCanvas.toDataURL('image/png');
                    const pieChartImage = pieChartCanvas.toDataURL('image/png');


                    if (barChartImage && pieChartImage &&
                        barChartImage.startsWith('data:image/png;base64,') &&
                        pieChartImage.startsWith('data:image/png;base64,')) {


                        document.getElementById('barChartImageInput').value = barChartImage;
                        document.getElementById('pieChartImageInput').value = pieChartImage;

                        document.getElementById('action').value = action;

                        document.getElementById('exportPdfForm').submit();
                    } else {
                        console.error("Canvas conversion to image failed or invalid base64 string.");
                    }
                } else {
                    console.error("Canvas elements have invalid dimensions or are empty.");
                }
            } else {
                console.error("Canvas elements not found.");
            }
        }
    </script>
    <div id="analytics-table" class="overflow-y-auto h-[200px] mx-2 custom-scrollbar">
        <table class="w-full">
            <thead class="bg-gray-200 sticky top-0 z-10">
                <tr class="p-2">
                    <th class="text-center">Rentee</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Property</th>
                    <th class="text-center">Destination</th>
                    <th class="text-center">Date Rent Start</th>
                    <th class="text-center">Date End Start</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <td class="text-center px-6 py-3">{{$record->reservation->rentee->name}}</td>
                        <td class="text-center px-6 py-3">{{$record->category->title}}</td>
                        <td class="text-center px-6 py-3">{{$record->property->name}}</td>
                        <td class="text-center px-6 py-3">{{$record->destination->municipality}}</td>
                        <td class="text-center px-6 py-3">{{$record->date_start}} {{$record->time_start}}</td>
                        <td class="text-center px-6 py-3">{{$record->date_end}} {{$record->time_end}}</td>
                        <td class="text-center px-6 py-3">{{ $record->reservation->status }}</td>
                        <td class="text-center px-6 py-3 text-center">
                            <button onclick="document.getElementById('preview-{{$record->id}}').classList.toggle('hidden')">
                                <i class="fas fa-eye text-blue-500 hover:opacity-100 opacity-50"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
    <div class="flex justify-center mt-2">
        <button onclick="document.getElementById('analytics-table').classList.toggle('h-[200px]')"
            class="text-blue-500"><i class="fas fa-chevron-down fa-2xl"></i></button>

    </div>

</section>

@foreach ($records as $record)
    <div id="preview-{{$record->id}}"
        class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 hidden z-50">
        <div class="select-none bg-white rounded shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="px-2 text-xl font-medium">Preview Details</h1>
                <button onclick="document.getElementById('preview-{{$record->id}}').classList.toggle('hidden')"
                    class="px-2 text-2xl font-bold hover:opacity-50">
                    &times;
                </button>
            </div>

            <div class="p-2">
                <ul>
                    <li><strong>Name: </strong>{{$record->reservation->rentee->name}}</li>
                    <li><strong>Category: </strong> {{$record->category->title}}</li>
                    <li><strong>Property: </strong> {{$record->property->name}}</li>
                    <li><strong>Date Start: </strong>
                        {{Carbon\Carbon::parse($record->date_start . $record->time_start)->format('F j Y, h:i A')}}
                    </li>
                    <li><strong>Date End: </strong>
                        {{Carbon\Carbon::parse($record->date_end . $record->time_end)->format('F j Y, h:i A')}}
                    </li>
                    <li><strong>Status: </strong> {{ucfirst($record->reservation->status)}}</li>
                </ul>

            </div>

            <div class="flex justify-center p-2">
                <button onclick="document.getElementById('preview-{{$record->id}}').classList.toggle('hidden')"
                    class="px-4 py-2 text-gray-800 border border-gray-300 rounded hover:opacity-50">
                    Close
                </button>
            </div>
        </div>
        </di>

@endforeach

    @endsection