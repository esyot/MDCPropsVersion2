<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report - {{ $currentYear }}</title>
    <style>
        /* General Body Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F3F4F6;
            color: #4B5563;
            margin: 0;
            padding: 2rem;
        }

        /* Heading Styles */
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
    </style>
</head>

<body>

    <div class="container">
        <h1>Analytics Report for Year {{ $currentYear }}</h1>


        <!-- Reservation Data Section -->
        <section>
            <h2>Reservation Data</h2>

            <!-- Bar Chart Section -->
            <div>
                <h3>Bar Chart</h3>
                <div>
                    <img src="temp/{{$barChartImage}}" alt="Bar Chart">
                </div>
            </div>

            <!-- Pie Chart Section -->
            <div>
                <h3>Pie Chart</h3>
                <img src="temp/{{$pieChartImage}}" alt="Pie Chart">
            </div>
        </section>

        <!--  -->
        <!-- Statistics Section -->
        <section>
            <h2>Overall Statistics</h2>
            <table>
                <thead>
                    <tr>
                        <th>Statistic</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Users</td>
                        <td>{{ $usersCount }}</td>
                    </tr>
                    <tr>
                        <td>Rentees</td>
                        <td>{{ $renteesCount }}</td>
                    </tr>
                    <tr>
                        <td>Items</td>
                        <td>{{ $itemsCount }}</td>
                    </tr>
                    <tr>
                        <td>Categories</td>
                        <td>{{ $categoriesCount }}</td>
                    </tr>
                    <tr>
                        <td>Admins</td>
                        <td>{{ $adminsCount }}</td>
                    </tr>
                    <tr>
                        <td>Superadmins</td>
                        <td>{{ $superadminsCount }}</td>
                    </tr>
                    <tr>
                        <td>Cashiers</td>
                        <td>{{ $cashiersCount }}</td>
                    </tr>
                    <tr>
                        <td>Staffs</td>
                        <td>{{ $staffsCount }}</td>
                    </tr>
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