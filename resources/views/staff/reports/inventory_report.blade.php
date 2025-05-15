@extends('staff.layout')

@section('title', 'Inventory Report')

@section('content')

<h1 class="title">Reports</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Inventory Report</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                
                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Inventory Report</h4>
                        <div class="d-flex align-items-center">
                            <button id="btnPrint" class="btn btn-primary"><i class="bx bxs-printer"></i></button>
                        </div> 
                    </div>
                        <form method="GET" action="{{ route('staff.inventory.view') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="filter" class="form-control">
                                        <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="stockFilter" class="form-control">
                                        <option value="">Sort by Stock</option>
                                        <option value="stockAdded_asc" {{ request('stockFilter') == 'stockAdded_asc' ? 'selected' : '' }}>Stock Add: Low to High</option>
                                        <option value="stockAdded_desc" {{ request('stockFilter') == 'stockAdded_desc' ? 'selected' : '' }}>Stock Add: High to Low</option>
                                        <option value="stockSold_asc" {{ request('stockFilter') == 'stockSold_asc' ? 'selected' : '' }}>Stock Sold: Low to High</option>
                                        <option value="stockSold_desc" {{ request('stockFilter') == 'stockSold_desc' ? 'selected' : '' }}>Stock Sold: High to Low</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Filter</i></button>
                                    <a href="{{ route('staff.inventory.view') }}" class="btn btn-secondary">Reset Filter</a>
                                </div>
                            </div>
                        </form>

                    <div class="data">
                        <table id="orderTable" class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th class="id-column">ID</th>
                                    <th>PRODUCT NAME</th>
                                    <th>UNIT</th>
                                    <th>STOCK ADDED</th>
                                    <th>STOCK SOLD</th>
                                    <th>CURRENT STOCK</th>
                                    <th>CATEGORY</th>
                                    <th>SUPPLIER</th>
                                    <th>REORDER LEVEL</th>
                                    <th>STOCK ALERT THRESHOLD</th>
                                    <th>TOTAL VALUE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($inventory->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            No Results Found
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($inventory as $product)
                                        <tr class="text-center align-middle">
                                            <td class="id-column">{{ $product->product_id }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->unit_name }}</td>
                                            <td>{{ $product->stock_added }}</td>
                                            <td>{{ $product->stock_sold }}</td>
                                            <td>{{ $product->current_stock }}</td>
                                            <td>{{ $product->category_name }}</td>
                                            <td>{{ $product->supplier_name }}</td>
                                            <td>{{ $product->reorder_level }}</td>
                                            <td>{{ $product->stock_alert_threshold }}</td>
                                            <td>₱{{ number_format($product->total_value, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $inventory->links() }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('btnPrint').addEventListener('click', function () {
        document.querySelectorAll('.id-column').forEach(el => el.style.display = 'none');
        // Get the table's HTML
        const table = document.getElementById('orderTable').outerHTML;
        
        // Get the selected filter from the dropdown (or fallback to 'Monthly' if no filter is selected)
        const filter = "{{ request('filter', 'monthly') }}";

        // Set the title based on the selected filter
        let reportTitle = "Inventory Report";  // Default title

        if (filter === 'daily') {
            reportTitle = "Daily Inventory Report";
        } else if (filter === 'weekly') {
            reportTitle = "Weekly Inventory Report";
        } else if (filter === 'monthly') {
            reportTitle = "Monthly Inventory Report";
        } else if (filter === 'yearly') {
            reportTitle = "Yearly Inventory Report";
        }

        // Open a print window and include the table HTML
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>KC Enterprise Inventory Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        text-align: center;
                        line-height: 1.5;
                    }

                    h2 {
                        text-align: center; /* Center the heading */
                        margin-top: 30px;
                        font-size: 24px; /* Increase the font size for better visibility */
                        font-weight: bold; /* Make the heading bold */
                        color: #333; /* Darker color for the title */
                    }

                    img.logo {
                        width: 120px; /* Adjust the width of the logo */
                        height: auto;
                        margin-bottom: 10px; /* Increase space between logo and heading */
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 40px; /* Add more space above the table */
                        padding: 15px;
                    }

                    th, td {
                        border: 1px solid #000;
                        padding: 12px; /* Increased padding for better readability */
                        text-align: center;
                        vertical-align: middle; /* Vertically align the text in the cells */
                    }

                    th {
                        background-color: #f2f2f2; /* Light gray background for table headers */
                        font-size: 14px; /* Slightly smaller text for header cells */
                        font-weight: bold; /* Make headers bold */
                    }

                    td {
                        font-size: 12px; /* Smaller font size for data cells */
                        color: #555; /* Slightly lighter text color for data */
                    }

                    /* Add alternating row colors for better readability */
                    tr:nth-child(even) {
                        background-color: #f9f9f9; /* Light background for even rows */
                    }

                    /* Make the page content fit well on a printed page */
                    @media print {
                        body {
                            margin: 0;
                            padding: 10px;
                        }

                        h2 {
                            font-size: 26px;
                            margin-top: 20px;
                        }

                        table {
                            margin-top: 30px;
                        }

                        /* Remove margins from table to ensure it fits well */
                        table, th, td {
                            margin: 0;
                            padding: 10px;
                        }
                    }
                </style>
            </head>
            <body>
                <img class="logo" src="{{ asset('assets/images/kc.png') }}" alt="Logo">
                <h2>${reportTitle}</h2>
                ${table}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();

        setTimeout(() => {
            document.querySelectorAll('.id-column').forEach(el => el.style.display = '');
        }, 100);
    });
</script>

@endsection

