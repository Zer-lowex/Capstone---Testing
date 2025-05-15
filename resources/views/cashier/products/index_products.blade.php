@extends('cashier.layout')

@section('title', 'Products')

@section('content')

<h1 class="title">Products</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Products</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Product List</h4>
                        <div class="d-flex position-relative">
                            <input type="text" id="productSearch" class="form-control me-2 search-bar"
                                   placeholder="Search Product..." autocomplete="off">
                        </div>
                    </div>

                    <div class="data">
                        <table id="productTable" class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>UNIT</th>
                                    {{-- <th>COST PRICE</th> --}}
                                    <th>PRICE</th>
                                    <th>QUANTITY</th>
                                    <th>EXPIRATION DATE</th>
                                    <th>CATEGORY</th>
                                    {{-- <th>SUPPLIER</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product) 
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->unit->name }}</td>
                                    {{-- <td>₱{{ number_format($product->cost_price, 2) }}</td> --}}
                                    <td>₱{{ number_format($product->sell_price, 2) }}</td>
                                    <td>
                                        @if ($product->quantity <= $product->stock_alert_threshold)
                                            <span class="badge bg-danger px-3 py-1 fs-6">
                                                {{ $product->quantity }}
                                            </span>
                                        @elseif ($product->quantity <= $product->reorder_level)
                                            <span class="badge bg-warning text-dark px-3 py-1 fs-6">
                                                {{ $product->quantity }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 fs-6">
                                                {{ $product->quantity }}
                                            </span>
                                        @endif
                                    </td>                                                                        
                                    <td>
                                        @if ($product->expiration_date)
                                            @php
                                                $expirationDate = \Carbon\Carbon::parse($product->expiration_date);
                                                $now = \Carbon\Carbon::now();
                                                $nextWeek = $now->copy()->addWeek();
                                                $nextMonth = $now->copy()->addMonth();
                                            @endphp
                                    
                                            @if ($expirationDate->isToday() || $expirationDate->isAfter($now) && $expirationDate->isBefore($nextWeek))
                                                <span class="badge bg-danger px-3 py-1 fs-6">{{ $expirationDate->format('d/m/Y') }}</span>
                                            @elseif ($expirationDate->isAfter($nextWeek) && $expirationDate->isBefore($nextMonth))
                                                <span class="badge bg-warning text-dark px-3 py-1 fs-6">{{ $expirationDate->format('d/m/Y') }}</span>
                                            @else
                                                {{ $expirationDate->format('d/m/Y') }}
                                            @endif
                                        @else
                                            No Expiry Date
                                        @endif
                                    </td>                                                                    
                                    <td>{{ $product->category->name }}</td>
                                    {{-- <td>{{ $product->supplier->name }}</td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $products->links() }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#productSearch').on('keyup', function() {
        let searchTerm = $(this).val();

        // Check if the search term is empty
        if (searchTerm === '') {
            // Reload the original product list
            $.ajax({
                url: '{{ route('cashier.product.view') }}', // Using product.view route
                type: 'GET',
                success: function(response) {
                    let products = response.data; // Access products array
                    let tableData = '';
                    
                    products.forEach(function(product) {
                        var expirationDate = product.expiration_date ? new Date(product.expiration_date) : null;
                        var expirationDateFormatted = expirationDate ? expirationDate.toLocaleDateString('en-GB') : 'No Expiry Date';

                        let quantity = parseFloat(product.quantity);
                        let stockAlertThreshold = parseFloat(product.stock_alert_threshold);
                        let reorderLevel = parseFloat(product.reorder_level);

                        let expirationBadgeClass = '';
                            if (expirationDate) {
                                let now = new Date();
                                let nextWeek = new Date();
                                nextWeek.setDate(now.getDate() + 7);
                                let nextMonth = new Date();
                                nextMonth.setMonth(now.getMonth() + 1);

                                if (expirationDate.toDateString() === now.toDateString() || expirationDate <= nextWeek) {
                                    expirationBadgeClass = 'bg-danger'; // Danger if expiration date is today, next week, or already passed
                                } else if (expirationDate <= nextMonth) {
                                    expirationBadgeClass = 'bg-warning text-dark'; // Warning if expiration date is within the next month
                                }
                            }

                        let badgeClass = '';
                            if (quantity <= stockAlertThreshold) {
                                badgeClass = 'bg-danger';
                            } else if (quantity <= reorderLevel) {
                                badgeClass = 'bg-warning text-dark';
                            }

                        tableData += `
                            <tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.unit ? product.unit.name : 'No Unit'}</td>
                                <td>${product.sell_price_formatted || '₱'+parseFloat(product.sell_price).toFixed(2)}</td>
                                <td>
                                    ${badgeClass ? `<span class="badge ${badgeClass} px-3 py-1 fs-6">${product.quantity}</span>` : product.quantity}
                                </td>
                                <td>
                                    ${expirationBadgeClass ? `<span class="badge ${expirationBadgeClass} px-3 py-1 fs-6">${expirationDateFormatted}</span>` : expirationDateFormatted}
                                </td>
                                <td>${product.category ? product.category.name : 'No Category'}</td>
                            </tr>
                        `;
                    });

                    $('table#productTable tbody').html(tableData);
                    $('#paginationLinks').show(); // Show pagination again
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                }
            });
        } else {
            // Perform the search
            $.ajax({
                url: '{{ route('cashier.product.search') }}',
                type: 'GET',
                data: { term: searchTerm },
                success: function(data) {
                    let tableData = '';

                    if (data.length > 0) {
                        data.forEach(function(product) {
                            var expirationDate = product.expiration_date ? new Date(product.expiration_date) : null;
                            var expirationDateFormatted = expirationDate ? expirationDate.toLocaleDateString('en-GB') : 'No Expiry Date';

                            let quantity = parseFloat(product.quantity);
                            let stockAlertThreshold = parseFloat(product.stock_alert_threshold);
                            let reorderLevel = parseFloat(product.reorder_level);

                            let expirationBadgeClass = '';
                                if (expirationDate) {
                                    let now = new Date();
                                    let nextWeek = new Date();
                                    nextWeek.setDate(now.getDate() + 7);
                                    let nextMonth = new Date();
                                    nextMonth.setMonth(now.getMonth() + 1);

                                    if (expirationDate.toDateString() === now.toDateString() || expirationDate <= nextWeek) {
                                        expirationBadgeClass = 'bg-danger'; // Danger if expiration date is today, next week, or already passed
                                    } else if (expirationDate <= nextMonth) {
                                        expirationBadgeClass = 'bg-warning text-dark'; // Warning if expiration date is within the next month
                                    }
                                }

                            let badgeClass = '';
                                if (quantity <= stockAlertThreshold) {
                                    badgeClass = 'bg-danger';
                                } else if (quantity <= reorderLevel) {
                                    badgeClass = 'bg-warning text-dark';
                                }

                            tableData += `
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.unit ? product.unit : 'No Unit'}</td>
                                    <td>${product.sell_price_formatted || '₱'+parseFloat(product.sell_price).toFixed(2)}</td>
                                    <td>
                                        ${badgeClass ? `<span class="badge ${badgeClass} px-3 py-1 fs-6">${product.quantity}</span>` : product.quantity}
                                    </td>
                                    <td>
                                        ${expirationBadgeClass ? `<span class="badge ${expirationBadgeClass} px-3 py-1 fs-6">${expirationDateFormatted}</span>` : expirationDateFormatted}
                                    </td>
                                    <td>${product.category ? product.category : 'No Category'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="7">No results found</td></tr>`;
                    }

                    $('table#productTable tbody').html(tableData);
                    $('#paginationLinks').hide(); // Hide pagination
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                }
            });
        }
    });
});
</script>

@endsection

