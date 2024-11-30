@extends('admin.layouts.header')
@section('content')

<style>
    @media(orientation:portrait) {

        #analytics-charts {
            display: flex;
            flex-direction: column;
            align-items: end;
            height: 500px;
            overflow-y: auto;

        }

        #container-counts {
            display: flex;
            overflow-x: auto;
        }

    }

    #transactionChart {
        width: 100px;

    }
</style>

<header id="analytics-header" class="flex items-center p-2 shadow-md">
    <div class="flex items-center space-x-4 w-full">


        <form action="{{ route('admin.analytics-charts-custom-year') }}" action="GET">
            @csrf
            <div class="flex items-center space-x-4">
                <label for="" class="{{$setting->darkMode ? 'text-white' : ''}} flex w-20">Select Year:</label>
                <select onchange="this.form.submit()" name="year"
                    class="block border border-gray-300 shadow-inner rounded">

                    <option class="text-red-500" value="{{$currentYear}}">{{$currentYear}}</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
                    <option value="2031">2031</option>
                    <option value="2032">2032</option>
                    <option value="2033">2033</option>
                </select>

            </div>

        </form>
        <span class="border-r-2 py-4 border-white">

        </span>

        <div class="flex justify-between w-full">


            <form action="{{ route('admin.analytics-custom') }}" class="hover:opacity-100 opacity-50" method="GET">
                @csrf
                <input type="hidden" name="rentee" value="all">
                <input type="hidden" name="category" value="all">
                <input type="hidden" name="property" value="all">
                <input type="hidden" name="date_start" value="all">
                <input type="hidden" name="date_end" value="all">
                <button>
                    Custom
                    Analytics
                </button>

            </form>



            <form id="exportPdfForm" action="{{ route('admin.analytics-export-to-pdf') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="barChartImageInput" id="barChartImageInput" />
                <input type="hidden" name="pieChartImageInput" id="pieChartImageInput" />
                <input type="hidden" name="currentYear" value="{{ $currentYear }}" />
            </form>
            <div class="relative inline-block">
                <button class="px-4 hover:opacity-50" title="Options" id="optionsBtn">
                    <i class="fas fa-ellipsis-vertical"></i>
                </button>
                <div id="pdfOptionsMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10">
                    <ul class="text-sm">
                        <li>
                            <button type="button" onclick="triggerSubmitForm('view')"
                                class="flex justify-center px-4 py-2 text-gray-800 hover:bg-gray-100 rounded-t w-full">
                                View as .pdf
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="triggerSubmitForm('download')"
                                class="flex justify-center px-4 py-2 text-gray-800 hover:bg-gray-100 rounded-b w-full">
                                Download as .pdf
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <script>

            const optionsBtn = document.getElementById('optionsBtn');
            const pdfOptionsMenu = document.getElementById('pdfOptionsMenu');

            optionsBtn.addEventListener('click', function (event) {
                pdfOptionsMenu.classList.toggle('hidden');

                event.stopPropagation();
            });

            document.addEventListener('click', function (event) {

                if (!optionsBtn.contains(event.target) && !pdfOptionsMenu.contains(event.target)) {
                    pdfOptionsMenu.classList.add('hidden');
                }
            });

        </script>
    </div>
</header>



<section>
    <div id="analytics-charts" class="flex justify-between space-x-2 p-4">

        <div
            class="flex flex-col items-center w-full h-96 bg-gray-100 rounded {{ $setting->darkMode ? 'bg-gray-300' : 'bg-white border shadow-md'}}">
            <div class="flex items-center w-full justify-between">

                <div class="flex items-center space-x-1">

                </div>
            </div>

            <canvas id="transactionChart"></canvas>
        </div>

        <div
            class="flex justify-center items-center w-full h-96 rounded {{ $setting->darkMode ? 'bg-gray-300' : 'bg-white border shadow-md' }}">

            <div>

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



    <div class="">
        <h1 class="text-xl font-semibold px-4 {{ $setting->darkMode ? 'text-white' : ''}}">Overall Counts</h1>

    </div>

    <div id="container-counts" class="flex p-2">

        @include('admin.partials.counts')
    </div>

</section>




@endsection