@extends('owner.layout')

@section('title', 'Cashier Report')

@section('content')

<h1 class="title">Reports</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Cashier Report</a></li>
</ul>

<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Cashier Report</h4>
            <button id="btnPrint" class="btn btn-primary"><i class="bx bxs-printer"></i> Print</button>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('owner.cashierReport.view') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="filter" class="form-control">
                        <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="cashier_id" class="form-control">
                        <option value="">Select Cashier</option>
                        @foreach($cashiers as $cashier)
                            <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                                {{ $cashier->first_name }} {{ $cashier->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('owner.cashierReport.view') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if(request()->has('filter'))
            <!-- Report Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 mb-3 shadow-sm">
                        @if(request()->has('cashier_id') && $cashierName)
                            <p><strong>Name:</strong> {{ $cashierName }}</p>
                        @endif
                        <p><strong>Date:</strong> {{ $reportDate }}</p>
                        <p><strong>Time Range:</strong> {{ $timeRange }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 mb-3 shadow-sm">
                        <p><strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }}</p>
                        <p><strong>Total Transactions:</strong> {{ $totalTransactions }}</p>
                        <p><strong>Average Transaction Value:</strong> ₱{{ number_format($averageTransactionValue, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Inventory Updates -->
            <div class="card p-3 mb-3 shadow-sm">
                <h6 class="fw-bold">Inventory Updates</h6>
                <table class="table table-striped table-bordered text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemsSold as $item)
                        <tr>
                            <td>{{ $item['product_code'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                {{ $item['quantity'] }} 
                                {{ $item['quantity'] == 1 ? 'unit' : 'units' }}
                            </td>
                            <td>₱{{ number_format($item['revenue'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Additional Metrics -->
            <div class="row">
                <div class="card p-3 mb-3 shadow-sm">
                    <h6 class="fw-bold">Top-Selling Items</h6>
                    <table class="table table-striped table-bordered text-center align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Units Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topSellingItems as $item)
                            <tr>
                                <td>{{ $item['product_code'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>
                                    {{ $item['quantity'] }} 
                                    {{ $item['quantity'] == 1 ? 'unit' : 'units' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('btnPrint').addEventListener('click', function() {
        // Clone the entire report section
        const printContent = document.querySelector('.container').cloneNode(true);
        
        // Remove interactive elements
        printContent.querySelectorAll('button, form, .no-print').forEach(el => el.remove());
        
        // Open print window
        const printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cashier Performance Report</title>
                <style>
                    body { font-family: Arial; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header img { height: 60px; }
                    .header h2 { margin: 10px 0 5px; }
                    .report-info { margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                    th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .summary-card { border: 1px solid #eee; padding: 15px; margin-bottom: 15px; }
                    @media print {
                        body { padding: 0; margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="${window.location.origin}/assets/images/kc.png" alt="Company Logo">
                    <h2>Cashier Performance Report</h2>
                    <p>Generated on: ${new Date().toLocaleDateString()}</p>
                </div>
                ${printContent.innerHTML}
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            setTimeout(window.close, 300);
                        }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    });
</script>
@endsection
