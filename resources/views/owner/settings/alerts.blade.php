@extends('owner.layout-2')

@section('title', 'Alerts')

@section('content')

<h1 class="title">Product Alerts</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Product Alerts</a></li>
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
                                    <th>REORDER LEVEL</th>
                                    <th>STOCK ALERT THRESHOLD</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product) 
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->reorder_level }}</td>
                                        <td>{{ $product->stock_alert_threshold }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateProductLevelModal"
                                                    data-bs-product-id="{{ $product->id }}"
                                                    data-bs-product-name="{{ $product->name }}"
                                                    data-bs-reorder-level="{{ $product->reorder_level }}"
                                                    data-bs-stock-alert-threshold="{{ $product->stock_alert_threshold }}">
                                                <i class="bx bxs-edit"></i>
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
                </div>

                <!-- Popup form for Updating Product Level --> 
                <div class="modal fade" id="updateProductLevelModal" tabindex="-1" aria-labelledby="updateProductLevelModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="updateProductLevelModalLabel"></h5>
                                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form action="" method="POST" id="updateProductForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label>Reorder Level</label>
                                        <input type="number" name="reorder_level" id="reorderLevelInput" class="form-control" />
                                        @error('reorder_level') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Stock Alert Threshold</label>
                                        <input type="number" name="stock_alert_threshold" id="stockAlertThresholdInput" class="form-control" />
                                        @error('stock_alert_threshold') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success text-white">Update Product</button>
                                    </div>
                                </form>                                
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
    document.addEventListener('DOMContentLoaded', () => {
        const updateProductLevelModal = document.getElementById('updateProductLevelModal');
        const updateProductForm = document.getElementById('updateProductForm');
        const reorderLevelInput = document.getElementById('reorderLevelInput');
        const stockAlertThresholdInput = document.getElementById('stockAlertThresholdInput');
        const modalTitle = document.getElementById('updateProductLevelModalLabel');

        updateProductLevelModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const productId = button.getAttribute('data-bs-product-id');
            const productName = button.getAttribute('data-bs-product-name');
            const reorderLevel = button.getAttribute('data-bs-reorder-level');
            const stockAlertThreshold = button.getAttribute('data-bs-stock-alert-threshold');

            // Update modal content
            modalTitle.textContent = productName;
            reorderLevelInput.value = reorderLevel;
            stockAlertThresholdInput.value = stockAlertThreshold;

            // Update form action
            updateProductForm.action = `/owner/settings/alerts/update/${productId}`;
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('#productSearch').on('keyup', function() {
        let search = $(this).val();

        // Check if the search term is empty
        if (search === '') {
            $.ajax({
                url: '{{ route('owner.profile.alerts') }}',
                type: 'GET',
                success: function(response) {
                    let products = response.data; // Access products array
                    let tableData = '';
                    
                    products.forEach(function(product) {

                        tableData += `
                            <tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.reorder_level}</td>
                                <td>${product.stock_alert_threshold}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateProductLevelModal"
                                            data-bs-product-id="${product.id}"
                                            data-bs-product-name="${product.name}"
                                            data-bs-reorder-level="${product.reorder_level}"
                                            data-bs-stock-alert-threshold="${product.stock_alert_threshold}">
                                        <i class="bx bxs-edit"></i>
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
                url: '{{ route('owner.product.search') }}',
                type: 'GET',
                data: { term: search },
                success: function(data) {
                    let tableData = '';

                    if (data.length > 0) {
                        data.forEach(function(product) {

                            tableData += `
                            <tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.reorder_level}</td>
                                <td>${product.stock_alert_threshold}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateProductLevelModal"
                                            data-bs-product-id="${product.id}"
                                            data-bs-product-name="${product.name}"
                                            data-bs-reorder-level="${product.reorder_level}"
                                            data-bs-stock-alert-threshold="${product.stock_alert_threshold}">
                                        <i class="bx bxs-edit"></i>
                                    </button>
                                </td>   
                            </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="5">No results found</td></tr>`;
                    }

                    $('table#productTable tbody').html(tableData);
                    $('#paginationLinks').hide();
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

