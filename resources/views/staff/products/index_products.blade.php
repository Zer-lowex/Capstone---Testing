@extends('staff.layout')

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
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <input type="text" id="productSearch" class="form-control search-bar" 
                                   placeholder="Search Product..." autocomplete="off">
                        </div>
                    </div>                    

                    <div class="collapse mt-3" id="filterOptions">
                        <form id="filterForm">
                            <div class="row">
                                <!-- Category Filter -->
                                <div class="col-md-4">
                                    <label for="category" class="form-label">Category</label>
                                    <select id="category" name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <!-- Unit Filter -->
                                <div class="col-md-4">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select id="unit" name="unit" class="form-select">
                                        <option value="">All Units</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <!-- Supplier Filter -->
                                <div class="col-md-4">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <select id="supplier" name="supplier" class="form-select">
                                        <option value="">All Suppliers</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    
                            <div class="mt-3 text-end">
                                <button type="button" id="applyFilter" class="btn btn-primary">Apply Filter</button>
                                <button type="button" id="resetFilter" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
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
                                    <th>SUPPLIER</th>
                                    <th>ACTION</th>
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
                                    <td>{{ $product->category->name ?? 'No Category'}}</td>
                                    <td>
                                        <a href="{{ route('staff.supplier.view') }}?supplier_id={{ $product->supplier->id ?? '' }}" class="supplier-link">
                                            {{ $product->supplier->name ?? 'No Supplier' }}
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addProductModal"
                                                data-product-id="{{ $product->id }}" 
                                                data-product-name="{{ $product->name }}"
                                                data-product-unit="{{ $product->unit->name }}"> 
                                            <i class="bx bxs-cart-add"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="paginationLinks" class="pagination"> 
                            {{ $products->links() }}
                        </div>
                    </div>
                    
                    <!-- Popup form for New Product Stock -->
                    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                    <h5 class="modal-title fw-bold" id="addProductModalLabel">Add Product Stock</h5>
                                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('stock.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" id="product_id">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Quantity</label>
                                            <input type="number" name="quantity" class="form-control rounded-3" placeholder="Enter Quantity" />
                                            @error('quantity') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success float-end">Add Stock</button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
                    url: '{{ route('staff.product.view') }}', // Using product.view route
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
                                    <td>
                                        ${product.supplier_id ? 
                                            `<a href="{{ route('staff.supplier.view') }}?supplier_id=${product.supplier_id}" class="supplier-link">
                                                ${product.supplier?.name || 'No Supplier'}
                                            </a>`
                                            : 'No Supplier'
                                        }
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addProductModal"
                                                data-product-id="${product.id}" 
                                                data-product-name="${product.name}"
                                                data-product-unit="${product.unit ? product.unit.name : 'No Unit'}">
                                            <i class="bx bxs-cart-add"></i>
                                        </button>
                                    </td>
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
                    url: '{{ route('staff.product.search') }}',
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
                                        <td>
                                            ${product.supplier_id ? 
                                                `<a href="{{ route('staff.supplier.view') }}?supplier_id=${product.supplier_id}" class="supplier-link">
                                                    ${product.supplier?.name || 'No Supplier'}
                                                </a>`
                                                : 'No Supplier'
                                            }
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addProductModal"
                                                    data-product-id="${product.id}" 
                                                    data-product-name="${product.name}"
                                                    data-product-unit="${product.unit ? product.unit : 'No Unit'}">
                                                <i class="bx bxs-cart-add"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            tableData = `<tr><td colspan="9">No results found</td></tr>`;
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

        // Modal event binding for dynamically loaded content
        $(document).on('click', '[data-bs-target="#addProductModal"]', function (event) {
            var button = $(this);
            var productId = button.data('product-id');
            var productName = button.data('product-name');
            var productUnit = button.data('product-unit');

            var modal = $('#addProductModal');
            modal.find('.modal-title').text('Add Stock for ' + productName + ' (' + productUnit + ')');
            modal.find('#product_id').val(productId); // Set product ID in hidden input
        });

        $('#applyFilter').on('click', function () {
        const category = $('#category').val();
        const unit = $('#unit').val();
        const supplier = $('#supplier').val();

        $.ajax({
            url: '{{ route('staff.products.filter') }}', // Adjust this to your filter route
            type: 'GET',
            data: { category: category, unit: unit, supplier: supplier },
            success: function (response) {
                let tableData = '';

                if (response.data.length > 0) {
                    response.data.forEach(function (product) {
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
                                <td>
                                    ${product.supplier_id ? 
                                        `<a href="{{ route('staff.supplier.view') }}?supplier_id=${product.supplier_id}" class="supplier-link">
                                            ${product.supplier?.name || 'No Supplier'}
                                        </a>`
                                        : 'No Supplier'
                                    }
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addProductModal"
                                            data-product-id="${product.id}" 
                                            data-product-name="${product.name}"
                                            data-product-unit="${product.unit ? product.unit : 'No Unit'}">
                                        <i class="bx bxs-cart-add"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#paginationLinks').hide();
                } else {
                    tableData = `<tr><td colspan="9">No results found</td></tr>`;
                }

                $('#productTable tbody').html(tableData);
            },
            error: function (xhr) {
                console.error('Error:', xhr);
            }
        });
    });

    $('#resetFilter').on('click', function () {
        $('#filterForm')[0].reset(); // Reset all filter fields

        // Reload all products
        $.ajax({
            url: '{{ route('staff.product.view') }}', // Adjust this to your default product list route
            type: 'GET',
            success: function (response) {
                let products = response.data; // Access products array
                let tableData = '';

                products.forEach(function (product) {
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
                            <td>
                                ${product.supplier_id ? 
                                    `<a href="{{ route('staff.supplier.view') }}?supplier_id=${product.supplier_id}" class="supplier-link">
                                        ${product.supplier?.name || 'No Supplier'}
                                    </a>`
                                    : 'No Supplier'
                                }
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addProductModal"
                                        data-product-id="${product.id}" 
                                        data-product-name="${product.name}"
                                        data-product-unit="${product.unit ? product.unit : 'N/A'}"> <!-- Fix for unit -->
                                    <i class="bx bxs-cart-add"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#productTable tbody').html(tableData);
                $('#paginationLinks').show(); 
            },
            error: function (xhr) {
                console.error('Error:', xhr);
            }
            });
        });
    });
</script>




@endsection

