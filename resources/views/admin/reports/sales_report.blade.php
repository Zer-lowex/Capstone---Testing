@extends('admin.layout')

@section('title', 'Sales Report')

@section('content')

    <h1 class="title">Reports</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Sales Report</a></li>
    </ul>

    <div class="head d-flex align-items-center justify-content-between">
        <h4>Sales Report</h4>
        <div class="d-flex align-items-center">
            <button id="btnPrint" class="btn btn-primary"><i class="bx bxs-printer"></i></button>
        </div>
    </div>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="container-data">
                    <form method="GET" action="{{ route('admin.sales.view') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="filter" class="form-control">
                                    <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily
                                    </option>
                                    <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly
                                    </option>
                                    <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly
                                    </option>
                                    <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="profitFilter" class="form-control">
                                    <option value="">Sort by Profit</option>
                                    <option value="profit_asc"
                                        {{ request('profitFilter') == 'profit_asc' ? 'selected' : '' }}>Profit: Low to High
                                    </option>
                                    <option value="profit_desc"
                                        {{ request('profitFilter') == 'profit_desc' ? 'selected' : '' }}>Profit: High to Low
                                    </option>
                                    <option value="quantity_sold"
                                        {{ request('profitFilter') == 'quantity_sold' ? 'selected' : '' }}>Quantity Sold:
                                        High to Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</i></button>
                                <a href="{{ route('admin.sales.view') }}" class="btn btn-secondary">Reset Filter</a>
                            </div>
                        </div>
                    </form>

                    <div class="data">
                        <table id="orderTable" class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th class="hide-on-print">ID</th>
                                    <th>PRODUCT</th>
                                    <th>UNIT</th>
                                    <th>QTY SOLD</th>
                                    <th>UNIT PRICE</th>
                                    <th>COST PRICE</th>
                                    <th>GROSS REVENUE</th>
                                    <th>NET REVENUE</th>
                                    <th>PROFIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalGross = 0;
                                    $totalNet = 0;
                                    $totalProfit = 0;
                                    $totalVAT = 0; // Hidden but tracked
                                @endphp

                                @foreach ($sales as $sale)
                                    @php
                                        // VAT-aware calculations
                                        $grossRevenue = $sale->total_revenue; // VAT-inclusive
                                        $netRevenue = $grossRevenue / 1.12; // VAT-exclusive (real revenue)
                                        $vatAmount = $grossRevenue - $netRevenue;
                                        $profit = $netRevenue - $sale->cost_price * $sale->total_quantity;

                                        // Accumulate totals
                                        $totalGross += $grossRevenue;
                                        $totalNet += $netRevenue;
                                        $costPriceTotal = $sale->cost_price * $sale->total_quantity;
                                        $totalProfit += $profit;
                                        $totalVAT += $vatAmount;
                                    @endphp

                                    <tr>
                                        <td class="hide-on-print">{{ $sale->product_id }}</td>
                                        <td>{{ $sale->product_name }}</td>
                                        <td>{{ $sale->unit_name ?? 'N/A' }}</td>
                                        <td>{{ $sale->total_quantity }}</td>
                                        <td>₱{{ number_format($sale->sell_price, 2) }}</td>
                                        <td>₱{{ number_format($costPriceTotal, 2) }}</td>
                                        <td>₱{{ number_format($grossRevenue, 2) }}</td>
                                        <td>₱{{ number_format($netRevenue, 2) }}</td>
                                        <td class="fw-bold">₱{{ number_format($profit, 2) }}</td>
                                    </tr>
                                @endforeach

                                <!-- Summary Row -->
                                <tr class="fw-bold bg-light">
                                    <td colspan="6" class="text-end">TOTALS:</td>
                                    <td>₱{{ number_format($totalGross, 2) }}</td>
                                    <td>₱{{ number_format($totalNet, 2) }}</td>
                                    <td>₱{{ number_format($totalProfit, 2) }}</td>
                                </tr>

                                <!-- VAT Summary (Minimal) -->
                                <tr class="small">
                                    <td colspan="9" class="text-end text-muted">
                                        <strong>VAT Liability:</strong> ₱{{ number_format($totalVAT, 2) }} (12% of net
                                        revenue)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnPrint').addEventListener('click', function() {
            const originalColspan = document.querySelector('.fw-bold.bg-light td').colSpan;
            // Hide ID columns
            document.querySelectorAll('.hide-on-print').forEach(el => el.style.display = 'none');
            document.querySelector('.fw-bold.bg-light td').colSpan = originalColspan - 1;

            // Get the filter value (passed from PHP)
            const filter = "{{ request('filter', 'daily') }}";
            const reportTitles = {
                daily: "Daily Sales Report",
                weekly: "Weekly Sales Report",
                monthly: "Monthly Sales Report",
                yearly: "Yearly Sales Report"
            };
            const reportTitle = reportTitles[filter] || "Sales Report";

            // Absolute path for the logo
            const logoPath = window.location.origin + "/assets/images/kc.png";

            try {
                const printWindow = window.open('', '', 'width=800,height=600');
                if (!printWindow) {
                    window.print(); // Fallback
                    return;
                }

                printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>KC Enterprise ${reportTitle}</title>
                <style>
                    body { font-family: Arial; margin: 0; padding: 10px; }
                    h2 { font-size: 24px; margin: 20px 0; text-align: center; }
                    img.logo { height: 60px; margin: 10px auto; display: block; }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-top: 20px;
                        table-layout: auto;
                    }
                    th, td { 
                        border: 1px solid #ddd; 
                        padding: 8px; 
                        text-align: center; 
                    }
                    th { background-color: #f2f2f2; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    @media print {
                        body { padding: 0; }
                        table { margin-top: 10px; }
                    }
                </style>
            </head>
            <body>
                <img class="logo" src="${logoPath}" alt="KC Logo">
                <h2>${reportTitle}</h2>
                ${document.getElementById('orderTable').outerHTML}
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            window.close();
                        }, 200);
                    };
                <\/script>
            </body>
            </html>
        `);
                printWindow.document.close();
            } catch (e) {
                console.error("Print failed:", e);
                window.print(); // Fallback
            } finally {
                // Restore hidden columns
                setTimeout(() => {
                    document.querySelectorAll('.hide-on-print').forEach(el => el.style.display = '');
                    document.querySelector('.fw-bold.bg-light td').colSpan = originalColspan;
                }, 500);
            }
        });
    </script>

@endsection
