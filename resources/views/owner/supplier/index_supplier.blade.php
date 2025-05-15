@extends('owner.layout')

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
                            <span id="clearSearch"
                                style="display: none; cursor: pointer; position: absolute; right: 125px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-times"></i>
                            </span>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addSupplierModal">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="data">
                        <table class="table table-striped table-bordered text-center align-middle" id="supplierTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>COMPANY NAME</th>
                                    <th>PHONE</th>
                                    <th>ADDRESS</th>
                                    <th>EMAIL</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier) 
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->email }}</td>
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
                                    {{-- <td>
                                        <a href="{{ route('owner.supplier.edit', $supplier->id) }}" class="btn btn-success"><i class="bx bxs-edit"></i></a>
                                        <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteSupplierModal"
                                            data-id="{{ $supplier->id }}">
                                            <i class="bx bxs-trash-alt"></i>
                                        </button>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $suppliers->links() }}
                    </div>                  
                </div>

                <!-- Popup form for New Supplier -->
                <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="addSupplierModalLabel">Add Supplier</h5>
                                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form action="{{ route('owner.supplier.add') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Name</label>
                                        <input type="text" name="name" class="form-control rounded-3" placeholder="Enter Supplier Name" />
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email (Optional)</label>
                                        <input type="text" name="email" class="form-control rounded-3" placeholder="Enter Supplier Email" />
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="text" name="phone" class="form-control rounded-3" placeholder="Enter Supplier Phone" />
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address</label>
                                        <input type="text" name="address" class="form-control rounded-3" placeholder="Enter Supplier Address" />
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success text-white">Create Supplier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="deleteSupplierModalLabel">Delete Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                Are you sure you want to delete this Supplier?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form id="deleteSupplierForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
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

        $.ajax({
            url: '{{ route('owner.supplier.search') }}',
            type: 'GET',
            data: { term: searchTerm },
            success: function(data) {
                let tableData = '';

                if (data.length > 0) {
                    data.forEach(function(supplier) {
                        tableData += `
                            <tr>
                                <td>${supplier.id}</td>
                                <td>${supplier.name}</td>
                                <td>${supplier.phone}</td>
                                <td>${supplier.address}</td>
                                <td>${supplier.email}</td>
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
                    tableData = `<tr><td colspan="6">No results found</td></tr>`;
                }

                $('table#supplierTable tbody').html(tableData);
                $('#paginationLinks').hide();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
            }
        });
    });
});
</script>

<script> 
    document.addEventListener('DOMContentLoaded', function () {
        const deleteSupplierModal = document.getElementById('deleteSupplierModal');
        const deleteSupplierForm = document.getElementById('deleteSupplierForm');

        deleteSupplierModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            const supplierId = button.getAttribute('data-id');

            // Update the form action dynamically
            deleteSupplierForm.action = `/owner/supplier/delete/${supplierId}`;
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
                url: '/owner/supplier/details/' + supplierId,
                type: 'GET',
                success: function(supplier) {
                    // Set the supplier details in the modal
                    $('#supplierName').text(supplier.name);
                    $('#supplierPhone').text(supplier.phone);
                    $('#supplierAddress').text(supplier.address);
                    $('#supplierEmail').text(supplier.email ?? 'N/A');
                    
                    // Load products
                    $.ajax({
                        url: '/owner/supplier/products/' + supplierId,
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
            url: `/owner/suppliers/${supplierId}`,
            type: 'GET',
            success: function(supplier) {
                // Update supplier info
                $('#modalSupplierName').text(supplier.name);
                $('#modalSupplierAddress').text(supplier.address);
                $('#modalSupplierPhone').text(supplier.phone);
                $('#modalSupplierEmail').text(supplier.email || 'N/A');
                
                // Fetch products for this supplier
                $.ajax({
                    url: `/owner/suppliers/${supplierId}/products`,
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

