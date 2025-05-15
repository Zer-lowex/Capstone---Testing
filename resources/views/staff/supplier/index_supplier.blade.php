@extends('staff.layout')

@section('title', 'Supplier')

@section('content')
<h1 class="title">Supplier</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Supplier</a></li>
</ul>
    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Supplier List</h4>
                        <div class="d-flex position-relative">
                            <input type="text" id="supplierSearch" class="form-control me-2 search-bar"
                                placeholder="Search Supplier..." autocomplete="off">
                        </div>
                    </div>

                    <div class="data">
                        <table class="table table-striped table-bordered text-center align-middle" id="supplierTable">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>COMPANY NAME</th>
                                    <th>PHONE</th>
                                    <th>ADDRESS</th>
                                    <th>EMAIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier) 
                                <tr>
                                    {{-- <td>{{ $supplier->id }}</td> --}}
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->email ? $supplier->email : 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary detail-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal"
                                            data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}"
                                            data-phone="{{ $supplier->phone }}"
                                            data-address="{{ $supplier->address }}"
                                            data-email="{{ $supplier->email }}">
                                            <i class="bx bxs-inbox"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $suppliers->links() }}
                    </div>    
                </div>

                <!-- From Products to Supplier Detail and Product List Modal -->
                <div class="modal fade" id="supplierDetailModal" tabindex="-1" aria-labelledby="supplierDetailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="supplierDetailModalLabel">Supplier Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <span id="supplierName"></span></p>
                                        <p><strong>Address:</strong> <span id="supplierAddress"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phone:</strong> <span id="supplierPhone"></span></p>
                                        <p><strong>Email:</strong> <span id="supplierEmail"></span></p>
                                    </div>
                                </div>
                                
                                <h6>Products Supplied</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>PRODUCT NAME</th>
                                                <th>UNIT</th>
                                                <th>CATEGORY</th>
                                                <th>COST PRICE</th>
                                                <th>QUANTITY</th>
                                                <th>EXPIRATION DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplierProductsTable" class="text-center">
                                            <!-- Products will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Detail and Product List Modal -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="detailModalLabel">Supplier Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <span id="modalSupplierName"></span></p>
                                        <p><strong>Address:</strong> <span id="modalSupplierAddress"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phone:</strong> <span id="modalSupplierPhone"></span></p>
                                        <p><strong>Email:</strong> <span id="modalSupplierEmail"></span></p>
                                    </div>
                                </div>
                                
                                <h6>Products Supplied</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>PRODUCT NAME</th>
                                                <th>UNIT</th>
                                                <th>CATEGORY</th>
                                                <th>COST PRICE</th>
                                                <th>QUANTITY</th>
                                                <th>EXPIRATION DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productsTableBody" class="text-center">
                                            <!-- Products will be loaded via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        $('#supplierSearch').on('keyup', function() {
            let searchTerm = $(this).val();

            // Check if the search term is empty
            if (searchTerm === '') {
                // Reload the original supplier list
                $.ajax({
                    url: '{{ route('staff.supplier.view') }}', // Update with your supplier view route
                    type: 'GET',
                    success: function(response) {
                        let suppliers = response.data; // Access suppliers array
                        let tableData = '';

                        suppliers.forEach(function(supplier) {
                            tableData += `
                                <tr>
                                    <td>${supplier.name}</td>
                                    <td>${supplier.phone}</td>
                                    <td>${supplier.address}</td>
                                    <td>${supplier.email ? supplier.email : 'N/A'}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary detail-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                data-id="${supplier.id}"
                                                data-name="${supplier.name}"
                                                data-phone="${supplier.phone}"
                                                data-address="${supplier.address}"
                                                data-email="${supplier.email}">
                                                <i class="bx bxs-inbox"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        $('table#supplierTable tbody').html(tableData);
                        $('#paginationLinks').show(); // Show pagination again
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                    }
                });
            } else {
                // Perform the search
                $.ajax({
                    url: '{{ route('staff.supplier.search') }}', // Update with your supplier search route
                    type: 'GET',
                    data: { term: searchTerm },
                    success: function(data) {
                        let tableData = '';

                        if (data.length > 0) {
                            data.forEach(function(supplier) {
                                tableData += `
                                <tr>
                                    <td>${supplier.name}</td>
                                    <td>${supplier.phone}</td>
                                    <td>${supplier.address}</td>
                                    <td>${supplier.email ? supplier.email : 'N/A'}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary detail-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                data-id="${supplier.id}"
                                                data-name="${supplier.name}"
                                                data-phone="${supplier.phone}"
                                                data-address="${supplier.address}"
                                                data-email="${supplier.email}">
                                                <i class="bx bxs-inbox"></i>
                                        </button>
                                    </td>
                                </tr>
                                `;
                            });
                        } else {
                            tableData = `<tr><td colspan="5">No results found</td></tr>`;
                        }

                        $('table#supplierTable tbody').html(tableData);
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

<script>
    $(document).ready(function() {
        // Check if URL has supplier_id parameter
        const urlParams = new URLSearchParams(window.location.search);
        const supplierId = urlParams.get('supplier_id');
        
        if (supplierId) {
            // Show loading state
            $('#supplierProductsTable').html('<tr><td colspan="7" class="text-center">Loading products...</td></tr>');
            $('#supplierDetailModal').modal('show');
            
            // Load supplier details
            $.ajax({
                url: '/staff/supplier/details/' + supplierId,
                type: 'GET',
                success: function(supplier) {
                    // Set the supplier details in the modal
                    $('#supplierName').text(supplier.name);
                    $('#supplierPhone').text(supplier.phone);
                    $('#supplierAddress').text(supplier.address);
                    $('#supplierEmail').text(supplier.email ?? 'N/A');
                    
                    // Load products
                    $.ajax({
                        url: '/staff/supplier/products/' + supplierId,
                        type: 'GET',
                        success: function(products) {
                            var tableBody = $('#supplierProductsTable');
                            tableBody.empty();
                            
                            if (products.length > 0) {
                                products.forEach(function(product) {
                                    tableBody.append(`
                                        <tr>
                                            <td>${product.id}</td>
                                            <td>${product.name}</td>
                                            <td>${product.unit}</td>
                                            <td>${product.category}</td>
                                            <td>₱${parseFloat(product.cost_price).toFixed(2)}</td>
                                            <td>${product.quantity}</td>
                                            <td>${product.expiration_date}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tableBody.append('<tr><td colspan="7">No products found for this supplier</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error loading products:', xhr);
                            $('#supplierProductsTable').html('<tr><td colspan="7">Error loading products</td></tr>');
                        }
                    });
                },
                error: function(xhr) {
                    console.error('Error loading supplier details:', xhr);
                    $('#supplierName').text('Error loading supplier');
                    $('#supplierProductsTable').html('<tr><td colspan="7">Error loading supplier details</td></tr>');
                }
            });
        }
        
        // Remove the supplier_id from URL after modal is closed
        $('#supplierDetailModal').on('hidden.bs.modal', function () {
            if (urlParams.has('supplier_id')) {
                urlParams.delete('supplier_id');
                window.history.replaceState({}, '', `${location.pathname}?${urlParams}`);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
    // When modal is about to be shown
    $('#detailModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const supplierId = button.data('id');
        
        // Set loading states
        $('#modalSupplierName, #modalSupplierAddress, #modalSupplierPhone, #modalSupplierEmail').text('Loading...');
        $('#productsTableBody').html('<tr><td colspan="7" class="text-center">Loading products...</td></tr>');
        
        // Fetch supplier details
        $.ajax({
            url: `/staff/suppliers/${supplierId}`,
            type: 'GET',
            success: function(supplier) {
                // Update supplier info
                $('#modalSupplierName').text(supplier.name);
                $('#modalSupplierAddress').text(supplier.address);
                $('#modalSupplierPhone').text(supplier.phone);
                $('#modalSupplierEmail').text(supplier.email || 'N/A');
                
                // Fetch products for this supplier
                $.ajax({
                    url: `/staff/suppliers/${supplierId}/products`,
                    type: 'GET',
                    success: function(products) {
                        const tableBody = $('#productsTableBody');
                        tableBody.empty();
                        
                        if (products.length > 0) {
                            products.forEach(function(product) {
                                const formattedPrice = parseFloat(product.cost_price).toFixed(2);
                                const expirationDate = product.expiration_date 
                                    ? new Date(product.expiration_date).toLocaleDateString() 
                                    : 'No Expiry';
                                
                                tableBody.append(`
                                    <tr>
                                        <td>${product.id}</td>
                                        <td>${product.name}</td>
                                        <td>${product.unit?.name || 'N/A'}</td>
                                        <td>${product.category?.name || 'N/A'}</td>
                                        <td>₱${formattedPrice}</td>
                                        <td>${product.quantity}</td>
                                        <td>${expirationDate}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="7" class="text-center">No products found for this supplier</td></tr>');
                        }
                    },
                    error: function() {
                        $('#productsTableBody').html('<tr><td colspan="7" class="text-center">Error loading products</td></tr>');
                    }
                });
            },
            error: function() {
                $('#modalSupplierName, #modalSupplierAddress, #modalSupplierPhone, #modalSupplierEmail').text('Error loading data');
                $('#productsTableBody').html('<tr><td colspan="7" class="text-center">Error loading supplier details</td></tr>');
            }
        });
    });
    
    // Clear modal content when closed
    $('#detailModal').on('hidden.bs.modal', function() {
        $('#modalSupplierName, #modalSupplierAddress, #modalSupplierPhone, #modalSupplierEmail').empty();
        $('#productsTableBody').empty();
    });
});
</script>

@endsection

