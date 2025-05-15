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
        @include('owner.partials.sidebar')
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        @include('owner.partials.navbar')
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
                        <i class="bx bx-user icon"></i>
                        <div>
                            <span class="count">{{ $customerCount }}</span>
                            <p>Customers</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bx-money icon"></i>
                        <div>
                            <span class="count">₱{{ number_format($monthlyProfit, 2) }}</span>
                            <p>Monthly Profit</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bx-shopping-bag icon"></i>
                        <div>
                            <span class="count">{{ $saleCount }}</span>
                            <p>Orders</p>
                        </div>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="head">
                        <i class="bx bxs-truck icon"></i>
                        <div>
                            <span class="count">{{ $supplierCount }}</span>
                            <p>Suppliers</p>
                        </div>
                    </div>
                </div>
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
                        <i class="bx bx-money icon"></i>
                        <div>
                            <span class="count">₱{{ number_format($todayProfit, 2) }}</span>
                            <p>Daily Profit</p>
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
                        <i class="bx bx-package icon"></i>
                        <div>
                            <span class="count">{{ $deliveryCount }}</span>
                            <p>Deliveries</p>
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
            </div>

            <div class="data">
                <div class="content-data">
                    <div class="head">
                        <h3>Sales Report</h3>
                        <div class="btn-group">
                            <button class="btn btn-primary" id="btnDaily">Daily</button>
                            <button class="btn btn-outline-primary" id="btnWeekly">Weekly</button>
                            <button class="btn btn-outline-primary" id="btnYearly">Yearly</button>
                        </div>
                    </div>
                    <div class="chart">
                        <div id="dailyChart"></div> <!-- Chart 1 (Daily) -->
                        <div id="weeklyChart" style="display: none;"></div> <!-- Chart 2 (Weekly) -->
                        <div id="monthlyChart" style="display: none;"></div> <!-- Chart 3 (Monthly) -->
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
                        <a href="{{ route('owner.activityLog.view') }}" >View All</a>
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

        @include('owner.partials.footer')
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
                height: 250
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

        var dailyProfitData = @json($dailyProfitData);

        var days = [];
        var profits = [];

        dailyProfitData.forEach(function (item) {
            days.push('Day ' + item.day); 
            profits.push(item.profit);
        });

        var dailyOptions = {
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
                categories: days 
            },
            tooltip: {
                x: {
                    format: 'dd/MM' 
                }
            }
        };

        var dailyChart = new ApexCharts(document.querySelector("#dailyChart"), dailyOptions);
        dailyChart.render();

        // Weekly Chart Data
        var weeklyProfitData = @json($weeklyProfitData);

        var weeks = [];
        var weeklyProfits = [];

        weeklyProfitData.forEach(function (item) {
            weeks.push('Week ' + item.week);  
            weeklyProfits.push(item.profit);  
        });

        var weeklyOptions = {
            series: [{
                name: 'Profit',
                data: weeklyProfits
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
                categories: weeks
            },
            tooltip: {
                x: {
                    format: 'Week'
                }
            }
        };

        var weeklyChart = new ApexCharts(document.querySelector("#weeklyChart"), weeklyOptions);
        weeklyChart.render();

        // Monthly Chart Data
        var yearlyProfitData = @json($yearlyProfitData);

        var monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        var months = [];
        var monthlyProfits = [];

        yearlyProfitData.forEach(function (item) {
            months.push(monthNames[item.month - 1]); 
            monthlyProfits.push(item.profit);  
        });

        var monthlyOptions = {
            series: [{
                name: 'Profit',
                data: monthlyProfits
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
                categories: months
            },
            tooltip: {
                x: {
                    formatter: function (val) {
                        return val; 
                    }
                }
            }
        };

        var monthlyChart = new ApexCharts(document.querySelector("#monthlyChart"), monthlyOptions);
        monthlyChart.render();


        // Button Click Events for Displaying the Charts
        document.getElementById('btnDaily').addEventListener('click', function () {
            document.getElementById('dailyChart').style.display = 'block';
            document.getElementById('weeklyChart').style.display = 'none';
            document.getElementById('monthlyChart').style.display = 'none';
            dailyChart.render();
        });

        document.getElementById('btnWeekly').addEventListener('click', function () {
            document.getElementById('dailyChart').style.display = 'none';
            document.getElementById('weeklyChart').style.display = 'block';
            document.getElementById('monthlyChart').style.display = 'none';
            weeklyChart.render();
        });

        document.getElementById('btnYearly').addEventListener('click', function () {
            document.getElementById('dailyChart').style.display = 'none';
            document.getElementById('weeklyChart').style.display = 'none';
            document.getElementById('monthlyChart').style.display = 'block';
            monthlyChart.render();
        });
        
    </script>

</body>

</html>
