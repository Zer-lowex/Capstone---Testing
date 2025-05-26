@extends('admin.layout')

@section('title', 'Driver Report')

@section('content')

    <h1 class="title">Reports</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Driver Report</a></li>
    </ul>

    <div class="container mt-4">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Delivery Report</h4>
                <button id="btnPrint" class="btn btn-primary"><i class="bx bxs-printer"></i> Print</button>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.deliveryReport.view') }}" class="mb-4">
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
                        <select name="driver_id" class="form-control">
                            <option value="">Select Driver</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}"
                                    {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.deliveryReport.view') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            @if (request()->has('filter'))
                <!-- Report Summary -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card p-3 mb-3 shadow-sm">
                            @if (request()->has('driver_id') && $driverName)
                                <p><strong>Driver:</strong> {{ $driverName }}</p>
                            @endif
                            <p><strong>Date:</strong> {{ $reportDate }}</p>
                            <p><strong>Time Range:</strong> {{ $timeRange }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3 mb-3 shadow-sm">
                            <p><strong>Total Deliveries:</strong> {{ $totalDeliveries }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deliveries Table -->
                <div class="card p-3 mb-3 shadow-sm">
                    <h6 class="fw-bold">Deliveries Made</h6>
                    <table class="table table-striped table-bordered text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Delivery ID</th>
                                <th>Customer</th>
                                <th>Customer Address</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deliveries as $delivery)
                                <tr>
                                    <td>{{ $delivery->id }}</td>
                                    <td>{{ $delivery->sale->customer->first_name }} {{ $delivery->sale->customer->last_name }}</td>
                                    <td>{{ $delivery->sale->customer->address }}</td>
                                    <td>{{ $delivery->updated_at->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $delivery->status == 'COMPLETE' ? 'success' : ($delivery->status == 'PENDING' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No deliveries found for the selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $deliveries->withQueryString()->links() }}
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
                <title>Driver Performance Report</title>
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
                    <h2>Driver Performance Report</h2>
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
