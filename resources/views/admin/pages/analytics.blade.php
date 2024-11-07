@extends('admin.layouts.header')
@section('content')
<header id="analytics-header" class="flex items-center justify-between p-2 ">
    <form action="{{ route('admin.analytics-charts-custom-year') }}" action="GET">
        @csrf
        <div class="flex items-center space-x-2">
            <label for="">Select Year:</label>
            <select onchange="this.form.submit()" name="year"
                class="block p-2 border border-gray-300 shadow-inner rounded">

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

    <div>
        <button class="px-4 hover:opacity-50" title="Options">
            <i class="fas fa-ellipsis-vertical"></i>
        </button>
    </div>
</header>

<section>
    <div id="analytics-charts" class="flex justify-between space-x-2 p-4">

        <div class="flex flex-col items-center w-full h-96 bg-gray-100 rounded">
            <div class="flex items-center w-full justify-between">
                <h2 class="text-xl font-semibold text-gray-800 text-center">
                    Monthly Reservation Counts of year {{$currentYear}}
                </h2>
                <div class="flex items-center space-x-1">

                </div>
            </div>


            <canvas id="transactionChart" class="w-full h-full"></canvas>
        </div>

        <div class="flex flex-col items-center w-full h-96 bg-gray-100 rounded">
            <h2 class="text-xl font-semibold text-gray-800 text-center mb-4">Overall Reservation Counts of year
                {{$currentYear}}
            </h2>

            <div class="relative">
                <canvas id="myPieChart"></canvas>
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
                        {{$januaryCanceledCount}},
                        {{$februaryCanceledCount}},
                        {{$marchCanceledCount}},
                        {{$aprilCanceledCount}},
                        {{$mayCanceledCount}},
                        {{$juneCanceledCount}},
                        {{$julyCanceledCount}},
                        {{$augustCanceledCount}},
                        {{$septemberCanceledCount}},
                        {{$octoberCanceledCount}},
                        {{$novemberCanceledCount}},
                            {{$decemberCanceledCount}}
                        ],
                        backgroundColor: 'rgba(255, 193, 7, 0.6)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Declined',
                        data: [
                        {{$januaryDeclinedCount}},
                        {{$februaryDeclinedCount}},
                        {{$marchDeclinedCount}},
                        {{$aprilDeclinedCount}},
                        {{$mayDeclinedCount}},
                        {{$juneDeclinedCount}},
                        {{$julyDeclinedCount}},
                        {{$augustDeclinedCount}},
                        {{$septemberDeclinedCount}},
                        {{$octoberDeclinedCount}},
                        {{$novemberDeclinedCount}},
                            {{$decemberDeclinedCount}}
                        ],
                        backgroundColor: 'rgba(244, 67, 54, 0.6)',
                        borderColor: 'rgba(244, 67, 54, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed',
                        data: [
                        {{$januaryCompletedCount}},
                        {{$februaryCompletedCount}},
                        {{$marchCompletedCount}},
                        {{$aprilCompletedCount}},
                        {{$mayCompletedCount}},
                        {{$juneCompletedCount}},
                        {{$julyCompletedCount}},
                        {{$augustCompletedCount}},
                        {{$septemberCompletedCount}},
                        {{$octoberCompletedCount}},
                        {{$novemberCompletedCount}},
                            {{$decemberCompletedCount}}
                        ],
                        backgroundColor: 'rgba(76, 175, 80, 0.6)',
                        borderColor: 'rgba(76, 175, 80, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
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
                data: [{{$itemsCanceledCount}}, {{$itemsDeclinedCount}}, {{$itemsCompletedCount}}],
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
    </script>



    <div class="flex p-2">
        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $usersCount }}</span>
                    <i class="fas fa-users text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Users</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>

        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $renteesCount }}</span>
                    <i class="fas fa-people-group text-blue-500"></i>


                </div>

                <h1 class="font-medium text-gray-500">Rentees</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>

        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $itemsCount }}</span>
                    <i class="fas fa-boxes text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Items</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>

        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $categoriesCount }}</span>
                    <i class="fas fa-sitemap text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Categories</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>
        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $superadminsCount }}</span>
                    <i class="fas fa-user-gear text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Superadmins</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>

        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $adminsCount }}</span>
                    <i class="fas fa-user-tie text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Admins</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>

        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $staffsCount }}</span>
                    <i class="fas fa-user text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Staffs</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>



        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $cashiersCount }}</span>
                    <i class="fas fa-user-tag text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">Cashiers</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>
    </div>


</section>



@endsection