<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report Custom</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #4B5563;

        }


        h1 {
            font-size: 2.25rem;
            font-weight: 700;
            text-align: center;
            color: #4F46E5;
            margin-bottom: 2rem;
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #4F46E5;
            margin-bottom: 1.5rem;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 1rem;
        }

        /* Section Styles */
        section {
            background-color: #FFFFFF;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        table th,
        table td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #E5E7EB;
        }

        table th {
            background-color: #F9FAFB;
            color: #4F46E5;
        }

        table td {
            color: #374151;
        }

        /* Cards for Statistics */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-card {
            background-color: #E5E7EB;
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 1.25rem;
            color: #4F46E5;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1.75rem;
            font-weight: bold;
            color: #374151;
        }

        /* Image Styles */
        img {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
            width: 100%;
            max-width: 100%;
        }

        /* Footer Styles */
        footer {
            text-align: center;
            font-size: 0.875rem;
            color: #6B7280;
            margin-top: 2rem;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            h2 {
                text-align: center;
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.25rem;
            }

            .stat-cards {
                grid-template-columns: 1fr;
            }
        }

        /* General container for the chart and statistics */


        .pie-chart-container {
            display: flex;
            flex-direction: column;
            justify-content: center;

        }

        .pie-chart-container img {
            width: 100%;

            display: block;
        }

        .pie-chart {
            display: block;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            max-width: 600px;
            margin: 0 auto 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Container for overall statistics */
        .statistics-container {
            margin-top: 30px;
        }

        /* Title for the overall statistics section */
        .statistics-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Individual statistics item */
        .statistics-item {
            display: flex;
            align-items: center;
            margin: 10px 0;

        }

        /* Styling for the status squares (color indicators) */
        .status-square {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 15px;

        }

        /* Green square for completed reservations */
        #status-square-green {
            background-color: #28a745;
            padding: 10px;
            color: #28a745;
            margin: 10px;
        }

        /* Red square for declined reservations */
        #status-square-red {
            background-color: #dc3545;
            padding: 10px;
            color: #dc3545;
            margin: 10px;
        }

        /* Yellow square for canceled reservations */
        #status-square-yellow {
            background-color: #ffc107;
            padding: 10px;
            color: #ffc107;
            margin: 10px;

        }

        /* Text for each statistics item */
        .statistics-text {
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .chart-container {
                width: 95%;
                padding: 15px;
            }

            .statistics-title {
                font-size: 24px;
            }

            .statistics-text {
                font-size: 16px;
            }
        }

        /* Header Section Styles */
        header {
            padding: 20px;
            border-bottom: 2px solid #ccc;
            margin-bottom: 100px;
        }

        .header-content {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .logo {
            margin-right: 15px;
            width: 100px;
        }

        .school-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: center;

            color: #333;
        }


        .school-info h1 {
            margin: 0;
        }

        .school-info small {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">

            <div class="school-info">
                <img src="asset/logo/logo.png" alt="Mater Dei College Logo" class="logo">
                <h1>MDC Property Management System</h1>
                <small>Mater Dei College</small>
                <small>Brgy. Cabulijan, Tubigon, Bohol</small>
            </div>
        </div>
    </header>



    <div class="container">
        <h1>Analytics Report</h1>


        <!-- Reservation Data Section -->
        <section>


            <!-- Bar Chart Section -->
            <div class="chart-container">
                <h3>Bar Chart</h3>
                <div>
                    <img src="temp/{{$barChartImage}}" alt="Bar Chart">
                </div>
            </div>

            <!-- Pie Chart Section -->
            <div class="chart-container">
                <div class="pie-chart-container">
                    <h3 class="chart-title">Pie Chart</h3>
                    <img src="temp/{{$pieChartImage}}" alt="Pie Chart">

                </div>


            </div>
        </section>


        <section>
            <h2>Overall Statistics</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Property</th>
                        <th>Destination</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                        <tr class="bg-white border-b hover:bg-gray-100">
                            <td class="px-6 py-3">{{$record->reservation->rentee->name}}</td>
                            <td class="px-6 py-3">{{$record->category->title}}</td>
                            <td class="px-6 py-3">{{$record->property->name}}</td>
                            <td class="px-6 py-3">{{$record->destination->municipality}}</td>
                            <td class="px-6 py-3">{{$record->date_start}} {{$record->time_start}}</td>
                            <td class="px-6 py-3">{{$record->date_end}} {{$record->time_end}}</td>
                            <td class="px-6 py-3">{{ $record->reservation->status }}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </section>


        <!-- Footer -->
        <footer>
            <p>&copy; 2024 MDC Property Rental & Reservation System. All rights reserved.</p>
        </footer>
    </div>

</body>

</html>