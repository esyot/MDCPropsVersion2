@extends('admin.layouts.header')
@section('content')
<nav class="">

</nav>

<section>
    <div class="flex p-2">
        <div
            class="flex m-2 flex-col bg-white border w-[200px] rounded-xl transition-transform duration-300 ease-in-out hover:scale-90">
            <div class="p-2">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ $usersCount }}</span>
                    <i class="fas fa-users text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">No. of Users</h1>
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
                    <i class="fas fa-user-tag text-blue-500"></i>
                </div>

                <h1 class="font-medium text-gray-500">No. of Rentees</h1>
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

                <h1 class="font-medium text-gray-500">No. of Items</h1>
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

                <h1 class="font-medium text-gray-500">No. of Categories</h1>
            </div>
            <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
                <h1 class="text-white">View More</h1>
                <i class="fas fa-arrow-right text-white"></i>
            </div>
        </div>
    </div>
    <div class="flex justify-between space-x-2 p-4">

        <div class="flex flex-col items-center w-full h-96 bg-gray-100 rounded">
            <div class="flex items-center w-full justify-between">
                <h2 class="text-xl font-semibold text-gray-800 text-center">
                    Monthly Transaction Counts
                </h2>
                <div class="flex items-center space-x-1">
                    <h1>Year:</h1>
                    <select class="block p-2 border border-gray-300 rounded">
                        <option value="">2024</option>
                        <option value="">2025</option>
                        <option value="">2026</option>
                        <option value="">2027</option>
                        <option value="">2028</option>
                        <option value="">2028</option>
                        <option value="">2030</option>
                    </select>
                </div>


            </div>


            <canvas id="transactionChart" class="w-full h-full"></canvas>
        </div>

        <div class="flex flex-col items-center w-full h-96 bg-gray-100 rounded">
            <h2 class="text-xl font-semibold text-gray-800 text-center mb-4">Monthly Transaction Counts</h2>

            <div class="relative">
                <canvas id="myPieChart"></canvas>
            </div>
        </div>
    </div>

</section>

<script src="{{ asset('asset/js/chart.min.js') }}"></script>

<script>
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const transactionChart = new Chart(ctx, {
        type: 'bar',  // Using bar chart
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], // Months
            datasets: [

                {
                    label: 'Canceled',
                    data: [5, 8, 10, 12, 7, 9, 11, 8, 6, 4, 5, 9], // Example data for "canceled"
                    backgroundColor: 'rgba(255, 193, 7, 0.6)', // Yellow with 60% opacity
                    borderColor: 'rgba(255, 193, 7, 1)',       // Darker yellow with full opacity
                    borderWidth: 1
                },

                {
                    label: 'Declined',
                    data: [5, 3, 4, 2, 5, 7, 6, 4, 3, 2, 4, 3], // Example data for "declined"
                    backgroundColor: 'rgba(244, 67, 54, 0.6)', // Red with 60% opacity
                    borderColor: 'rgba(244, 67, 54, 1)',       // Dark red with full opacity
                    borderWidth: 1
                },

                {
                    label: 'Completed',
                    data: [30, 35, 40, 45, 50, 55, 60, 65, 70, 72, 75, 80], // Example data for "completed"
                    backgroundColor: 'rgba(76, 175, 80, 0.6)',   // Green with 60% opacity
                    borderColor: 'rgba(76, 175, 80, 1)',         // Darker green with full opacity
                    borderWidth: 1
                }


            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Position of the legend
                }
            },
            scales: {
                x: {
                    // Adjusting the x-axis so we have 6 bars for each month
                    stacked: false,  // We don't stack them, each status will be separate bars
                },
                y: {
                    beginAtZero: true, // Start the y-axis from 0
                    stacked: false, // No stacking of bars, separate bars for each dataset
                }
            }
        }
    });
</script>

<script>
    // Data for the pie chart
    const data = {
        labels: ['Red', 'Blue', 'Yellow'], // Pie chart labels
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100], // Pie chart data values
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };

    // Options for the pie chart (customizing look, animation, etc.)
    const options = {
        responsive: true,  // Makes the chart responsive
        plugins: {
            legend: {
                position: 'top',
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

    // Create the chart
    const asd = document.getElementById('myPieChart').getContext('2d');
    const myPieChart = new Chart(asd, {
        type: 'pie', // Specifies the chart type
        data: data,
        options: options
    });
</script>


@endsection