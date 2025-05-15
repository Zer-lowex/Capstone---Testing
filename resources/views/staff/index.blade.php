<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <title>Dashboard</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        @include('staff.partials.sidebar')
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        @include('staff.partials.navbar')
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <h1 class="title">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Dashboard</a></li>
            </ul>
            <div class="info-data">
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bx-box icon"></i>
                        <div>
                            <span class="count">{{ $productCount }}</span>
                            <p>Inventory</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bx-down-arrow-circle icon"></i>
                        <div>
                            <span class="count">{{ $lowStockCount }} | {{ $stockAlertThresholdCount }}</span>
                            <p>Low Stock</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bx-alarm-exclamation icon"></i>
                        <div>
                            <span class="count">{{ $almostExpiredCount }} | {{ $expiredCount }}</span>
                            <p>Expiry Date</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bxs-truck icon"></i>
                        <div>
                            <span class="count">{{ $deliveryCount }}</span>
                            <p>Deliveries</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bxs-store icon"></i>
                        <div>
                            <span class="count">{{ $supplierCount }}</span>
                            <p>Suppliers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="data">
                <div class="content-data">
                    <div class="head">
                        <h3>{{ \Carbon\Carbon::now()->format('F') }} Sales Report</h3>
                    </div>
                    <div class="chart">
                        <div id="salesReportChart"></div>
                    </div>
                </div>
            </div>
            <div class="data">
                <div class="content-data">

                    <div class="container-data">
                        <div class="head">
                            <h3><center>Top Products Sold</center></h3>
                        </div>
                        <div class="top-products">
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                    <div class="container-data">
                        <div class="head">
                            <center><h3>Top 5 Categories Sold</h3></center>
                        </div>
                        <div class="top-products">
                            <div id="pie-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities / Notifications -->
                <div class="content-data">
                    <div class="head">
                        <h3>Activity Log</h3>
                        <a href="{{ route('staff.activityLog.view') }}" >View All</a>
                    </div>
                    <div class="container-data">
                        <table class="table table-striped table-bordered text-center align-middle activity-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>DESCRIPTION</th>
                                    <th>TIME</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($activity_logs->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No records found for the selected filters.</td>
                                    </tr>
                                @else
                                    @foreach ($activity_logs as $activityLog)
                                        <tr>
                                            <td>{{ $activityLog->id }}</td>
                                            <td>{{ $activityLog->description }}</td>
                                            <td>{{ $activityLog->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
        </main>
        <!-- MAIN -->

        @include('staff.partials.footer')
    </section>
    <!-- NAVBAR -->

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        
        // TOP CATEGORIES

        var options = {
            chart: {
                type: 'pie',
                height: 200,
                width: '100%',
            },
            series: [
                @foreach ($topCategories as $category)
                    {{ $category['value'] }},
                @endforeach
            ],
            labels: [
                @foreach ($topCategories as $category)
                    '{{ $category['name'] }}',
                @endforeach
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
    
        var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
        chart.render();

        // TOP PRODUCTS

        var options = {
            chart: {
                type: 'bar',
                height: 260
            },
            series: [{
                name: 'Quantity Sold',
                data: [
                    @foreach ($topProducts as $product)
                        {{ $product['value'] }},
                    @endforeach
                ]
            }],
            xaxis: {
                categories: [
                    @foreach ($topProducts as $product)
                        '{{ $product['name'] }} ({{ $product['unit'] }})',
                    @endforeach
                ]
            },
            plotOptions: {
                bar: {
                    horizontal: false // Horizontal bar chart
                }
            },
            dataLabels: {
                enabled: true
            }
        };

        var chart = new ApexCharts(document.querySelector("#bar-chart"), options);
        chart.render();

        // SALES REPORT

        var dailyProfitData = @json($dailyProfitData); // Pass data from the controller to JS

        var days = [];
        var profits = [];

        // Prepare the data for daily chart
        dailyProfitData.forEach(function (item) {
            days.push('Day ' + item.day); // You can customize the day label format
            profits.push(item.profit);
        });

        var options = {
            series: [{
                name: 'Profit',
                data: profits
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: days // Now using days instead of months
            },
            tooltip: {
                x: {
                    format: 'dd/MM' // Showing the day in the tooltip
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#salesReportChart"), options);
        chart.render();

    </script>    

</body>

</html>
