<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <title>Dashboard</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        @include('cashier.partials.sidebar')
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        @include('cashier.partials.navbar')
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            {{-- <h1 class="title">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Dashboard</a></li>
            </ul> --}}
            {{-- @if (session('accepted_products'))
                <div class="card mt-3">
                    <div class="card-header">Debug - Accepted Products Data</div>
                    <div class="card-body">
                        <pre>{{ print_r(session('accepted_products'), true) }}</pre>
                    </div>
                </div>
            @endif --}}


            {{-- MAIN CONTAINER --}}
            <div class="container mt-4">
                <div class="row">
                    <!-- Search Section -->
                    <div class="col-md-8">
                        <div class="card p-3">
                            @if (session('accepted_products'))
                                <div class="mb-3">
                                    <button type="button" id="cancelReservationBtn" class="btn btn-danger">
                                        <i class="bx bx-x-circle"></i> Cancel Reservation
                                    </button>
                                </div>
                            @endif
                            <h5>Search</h5>
                            <div class="search-bar-container">
                                <input type="text" id="searchBar" class="form-control search-bar"
                                    placeholder="Search Product..." autocomplete="off">

                                <!-- Suggestions Dropdown -->
                                <ul id="suggestions" class="list-group">
                                    <!-- Suggestions will be appended here by jQuery -->
                                </ul>
                            </div>

                            <form id="salesForm" action="{{ route('cashier.addSale') }}" method="post">
                                @csrf
                                <!-- Product Table -->
                                <table class="table table-striped table-bordered text-center align-middle mt-3">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">ID</th>
                                            <th style="width: 30%;">NAME</th>
                                            <th>UNIT</th>
                                            <th>QUANTITY</th>
                                            <th>PRICE</th>
                                            <th>TOTAL</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="addMoreProduct">
                                        <!-- Rows will be dynamically added here -->

                                        @if ($errors->has('products'))
                                            <div class="text-danger">
                                                {{ $errors->first('products') }}
                                            </div>
                                        @endif

                                        @foreach (old('products', []) as $index => $product)
                                            <tr data-id="{{ $product['id'] }}">
                                                <td>{{ $product['id'] }}</td>
                                                <td>{{ $product['name'] }}</td>
                                                <td>{{ $product['unit'] }}</td>
                                                <td>
                                                    <input type="number"
                                                        name="products[{{ $product['id'] }}][quantity]"
                                                        class="form-control quantity"
                                                        value="{{ old('products.' . $product['id'] . '.quantity', 1) }}"
                                                        step="1" min="1"
                                                        style="width: 80px; margin: 0 auto; display: block;">
                                                    @if ($errors->has('products.' . $product['id'] . '.quantity'))
                                                        <div class="text-danger">
                                                            {{ $errors->first('products.' . $product['id'] . '.quantity') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="hidden" name="products[{{ $product['id'] }}][id]"
                                                        value="{{ $product['id'] }}">
                                                    <input type="hidden" name="products[{{ $product['id'] }}][name]"
                                                        value="{{ $product['name'] }}">
                                                    <input type="hidden" name="products[{{ $product['id'] }}][unit]"
                                                        value="{{ $product['unit'] }}">
                                                    <input type="hidden" name="products[{{ $product['id'] }}][price]"
                                                        value="{{ $product['price'] }}">
                                                    {{ $product['price'] }}
                                                    @if ($errors->has('products.' . $product['id'] . '.price'))
                                                        <div class="text-danger">
                                                            {{ $errors->first('products.' . $product['id'] . '.price') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="products[{{ $product['id'] }}][total_amount]"
                                                        class="form-control total_amount"
                                                        value="{{ old('products.' . $product['id'] . '.total_amount', $product['price']) }}"
                                                        readonly>
                                                    @if ($errors->has('products.' . $product['id'] . '.total_amount'))
                                                        <div class="text-danger">
                                                            {{ $errors->first('products.' . $product['id'] . '.total_amount') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm delete"><i
                                                            class="bx bx-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-2">
                            <h5 class="mb-1" style="font-size: 1rem;">Order Summary</h5>
                            <div class="order-summary" style="font-size: 0.85rem;">
                                <!-- Subtotal (VAT inclusive) -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-semibold">
                                        <span class="currency">₱</span>
                                        <span
                                            id="subtotalDisplay">{{ number_format(old('finalAmount', 0.0), 2) }}</span>
                                    </span>
                                </div>

                                <!-- Total Amount -->
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fw-bold text-primary">
                                        <span class="currency">₱</span>
                                        <span id="total">{{ number_format(old('finalAmount', 0.0), 2) }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Hidden fields for form submission -->
                            <input type="hidden" id="finalAmountInput" name="finalAmount"
                                value="{{ number_format(old('finalAmount', 0), 2, '.', '') }}">
                        </div>

                        <div class="card p-3">
                            {{-- Customer Select --}}
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3 position-relative">
                                        <select id="customerSelect" name="customer_id" class="form-select pe-4"
                                            aria-label="Select customer" required>
                                            <option value="" selected>WALK-IN</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-address="{{ $customer->address }}"
                                                    @if (old('customer_id') == $customer->id) selected @endif>
                                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addCustomerModal">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('customer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <!-- Delivery Checkbox -->
                            <div class="mb-3 form-check">
                                <input type="hidden" name="delivery" value="NO">
                                <input type="checkbox" class="form-check-input" id="deliveryCheckbox"
                                    name="delivery" value="YES" onchange="updateDeliveryStatus(this)">
                                <label class="form-check-label" for="deliveryCheckbox">Delivery</label>
                                {{-- <span id="deliveryStatus" class="ms-2 fw-bold">NO</span> --}}
                            </div>

                            <!-- Customer Address -->
                            <div id="customerAddressContainer" class="mb-3" style="display: none;">
                                <label for="customerAddress">Customer Address</label>
                                <input type="text" id="customerAddress" name="customer_address"
                                    class="form-control" placeholder="Enter Address"
                                    value="{{ old('customer_address') }}">
                                @error('customer_address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Delivery Fee -->
                            <div id="deliveryFeeContainer" class="mb-3" style="display: none;">
                                <label for="deliveryFee">Delivery Fee</label>
                                <input type="number" id="deliveryFee" name="delivery_fee" class="form-control"
                                    placeholder="Enter Delivery Fee" value="{{ old('delivery_fee') }}">
                                @error('delivery_fee')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Discount -->
                            <div class="mb-3">
                                <label for="discountType">Discount</label>
                                <select name="discount_type" id="discountType" class="form-select">
                                    <option value="" selected>No Discount</option>
                                    <option value="senior/pwd"
                                        {{ old('discount_type') == 'senior' ? 'selected' : '' }}>Senior/PWD Customer
                                        (20%)</option>
                                    <option value="custom" {{ old('discount_type') == 'custom' ? 'selected' : '' }}>
                                        Custom Discount</option>
                                </select>
                                @error('discount_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Custom Discount Input (hidden by default) -->
                            <div id="customDiscountContainer" class="mb-3" style="display: none;">
                                <label for="customDiscount">Custom Discount Value</label>
                                <div class="input-group">
                                    <input type="number" name="custom_discount_value" id="customDiscountValue"
                                        class="form-control" placeholder="Enter discount value"
                                        value="{{ old('custom_discount_value') }}" min="0">
                                    <select name="custom_discount_type" id="customDiscountType" class="form-select">
                                        <option value="peso"
                                            {{ old('custom_discount_type') == 'peso' ? 'selected' : '' }}>Peso</option>
                                        <option value="percent"
                                            {{ old('custom_discount_type') == 'percent' ? 'selected' : '' }}>%</option>
                                    </select>
                                </div>
                                @error('custom_discount_value')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('custom_discount_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hidden field for final discount amount that will be sent to server -->
                            <input type="hidden" name="discount" id="discountInput"
                                value="{{ old('discount', 0) }}">
                            <input type="hidden" name="discount_percentage" id="discountPercentageInput"
                                value="0">

                            <!-- Payment -->
                            <div class="mb-3">
                                <label for="paid_amount">Payment</label>
                                <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                                    placeholder="Enter Payment Amount" value="{{ old('paid_amount') }}">
                                @error('paid_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Change -->
                            <div class="mb-3">
                                <label for="balance">Change</label>
                                <input type="number" readonly name="balance" id="balance" class="form-control"
                                    value="{{ old('balance') }}">
                                @error('balance')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="button" id="submitBtn"
                                class="btn-primary btn-lg btn-block mt-4">Save</button>
                            {{-- <button type="button" onclick="PrintReceiptContent('print')" class="btn-success btn-lg btn-block mt-2">Print</button> --}}
                        </div>
                    </div>
                </div>

                </form>

                <!-- Quantity Modal -->
                <div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="quantityModalLabel">
                                    Enter Quantity for <span id="modalProductNameDisplay"></span>
                                </h5>
                                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                                    aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="quantityForm">
                                    <input type="hidden" id="modalProductId">
                                    <input type="hidden" id="modalCodeId">
                                    <input type="hidden" id="modalProductName">
                                    <input type="hidden" id="modalProductUnit">
                                    <input type="hidden" id="modalProductPrice">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" for="modalQuantity">Quantity</label>
                                        <input type="number" id="modalQuantity" class="form-control rounded-3"
                                            step="1" min="1" value="1"
                                            placeholder="Enter Quantity">
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-light border"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary text-white" id="addToTable">Add
                                            Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popup form for New Customer -->
                <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="addCustomerModalLabel">Add Customer</h5>
                                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                                    aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <form action="{{ url('/cashier/customers/add') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">First Name</label>
                                        <input type="text" name="first_name" class="form-control rounded-3"
                                            placeholder="Enter First Name" />
                                        @error('first_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Last Name</label>
                                        <input type="text" name="last_name" class="form-control rounded-3"
                                            placeholder="Enter Last Name" />
                                        @error('last_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Username</label>
                                        <input type="text" name="username" class="form-control rounded-3"
                                            placeholder="Enter Username Name" />
                                        @error('username')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="tel" name="phone" class="form-control rounded-3"
                                            placeholder="Enter Customer Phone" />
                                        @error('phone')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address</label>
                                        <input type="text" name="address" class="form-control rounded-3"
                                            placeholder="Enter Customer Address" />
                                        @error('address')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-light border"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success text-white">Create
                                            Customer</button>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-muted small">Note: The default password for the account is
                                            <strong>Password123</strong>.
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Confirmation Modal -->
                <div class="modal fade" id="saveConfirmationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Sale</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <strong>Paid Amount:</strong> ₱<span id="modalPaidAmount">0.00</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Change:</strong> <span class="text-success">₱<span
                                            id="modalChange">0.00</span></span>
                                </div>
                                <p>Are you sure you want to save this transaction?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmSave" class="btn btn-primary">Confirm &
                                    Save</button>
                                <button type="button" id="saveAndPrintBtn" class="btn btn-success">Save &
                                    Print</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to remove this product?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receipt --}}
                <div class="modal">
                    <div id="print">
                        @include('cashier.receipts.index_receipt2')
                    </div>
                </div>
            </div>

        </main>

        <!-- MAIN -->

        @include('cashier.partials.footer')
    </section>
    <!-- NAVBAR -->

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('stockErrors'))
            @foreach (session('stockErrors') as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all POS functionality
            initializePOS();

            // Handle reserved products if any
            @if (session('accepted_products'))
                const products = @json(session('accepted_products'));
                products.forEach(product => {
                    addProductToTable(product);
                });

                @if (session('customer_id'))
                    $('#customerSelect').val('{{ session('customer_id') }}').trigger('change');
                @endif

                setTimeout(() => {
                    calculateTotals();
                    if ($('#paid_amount').val()) {
                        $('#paid_amount').trigger('keyup');
                    }
                }, 200);
            @endif
        });

        function initializePOS() {
            $('#discountType').removeAttr('onchange');
            // Initialize Select2 for customer dropdown
            $('#customerSelect').select2({
                placeholder: 'Select Customer or WALK-IN',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#customerSelect').parent(),
                minimumResultsForSearch: 5
            });

            initDiscountControls();

            // Set up all event listeners using event delegation
            $(document)
                // Search functionality
                .on('input', '#searchBar', handleSearchInput)
                .on('click', '.suggestion-item', handleSuggestionClick)
                .on('change keyup', '.quantity', handleQuantityChange)
                .on('click', '.delete', handleDeleteClick)
                .on('click', '#addToTable', addProductFromModal)
                .on('keyup', '#paid_amount', handlePaymentChange)
                .on('change', '#discountType', function() {
                    handleDiscountChange(this);
                })
                .on('input change', '#customDiscountValue, #customDiscountType', calculateTotals)
                .on('change', '#deliveryCheckbox', handleDeliveryChange)
                .on('keyup', '#deliveryFee', calculateTotals)
                .on('change', '#customerSelect', handleCustomerChange)
                .on('click', '#submitBtn', handleSubmit)
                .on('click', '#confirmSave', () => submitForm(false))
                .on('click', '#saveAndPrintBtn', () => submitForm(true));

            updateDeliveryStatus($('#deliveryCheckbox')[0]);
        }

        // ==================== DISCOUNT SPECIFIC FUNCTIONS ====================

        function initDiscountControls() {
            // Initialize based on current selection
            const discountType = $('#discountType').val();
            const customDiscountContainer = $('#customDiscountContainer');

            if (discountType === 'custom') {
                customDiscountContainer.css('display', 'block');
            } else {
                customDiscountContainer.css('display', 'none');
            }

            calculateTotals();
        }

        function handleDiscountChange(select) {
            const discountType = $(select).val();
            const customDiscountContainer = $('#customDiscountContainer');

            if (discountType === 'custom') {
                customDiscountContainer.css('display', 'block');
                $('#customDiscountValue').val('');
                $('#customDiscountType').val('percent');
            } else {
                customDiscountContainer.css('display', 'none');
                $('#customDiscountValue').val('');
                $('#customDiscountType').val('percent');
            }

            calculateTotals();
        }

        // ==================== CALCULATION FUNCTIONS ====================

        function calculateTotals() {
            let subtotal = 0;
            $('.total_amount').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            // Add delivery fee if checked
            if ($('#deliveryCheckbox').is(':checked')) {
                subtotal += parseFloat($('#deliveryFee').val()) || 0;
            }

            // Calculate discount
            let discountAmount = 0;
            let discountPercentage = 0;
            const discountType = $('#discountType').val();

            if (discountType === 'senior/pwd') {
                discountPercentage = 20;
                discountAmount = subtotal * (discountPercentage / 100);
            } else if (discountType === 'custom') {
                const customValue = parseFloat($('#customDiscountValue').val()) || 0;
                const customType = $('#customDiscountType').val();

                if (customType === 'percent') {
                    discountPercentage = customValue;
                    discountAmount = subtotal * (discountPercentage / 100);
                } else {
                    discountPercentage = 0;
                    discountAmount = customValue;
                }
            }

            $('#discountInput').val(discountAmount.toFixed(2));
            $('#discountPercentageInput').val(discountPercentage);
            const finalTotal = subtotal - discountAmount;

            // Update displays
            $('#subtotalDisplay').text(subtotal.toFixed(2));
            $('#total').text(finalTotal.toFixed(2));
            $('#finalAmountInput').val(finalTotal.toFixed(2));

            // Update balance
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            $('#balance').val((paidAmount - finalTotal).toFixed(2));
        }

        // ==================== DELIVERY FUNCTIONS ====================

        function handleDeliveryChange() {
            if ($(this).is(':checked')) {
                $('#customerAddressContainer, #deliveryFeeContainer').show();
                updateCustomerAddress();
            } else {
                $('#customerAddressContainer, #deliveryFeeContainer').hide();
                $('#customerAddress, #deliveryFee').val('');
            }
            updateDeliveryStatus(this);
            calculateTotals(); // Ensure totals are recalculated
        }

        function updateDeliveryStatus(checkbox) {
            const $checkbox = $(checkbox);
            const hiddenInput = $('input[name="delivery"]');
            hiddenInput.val($checkbox.is(':checked') ? 'YES' : 'NO');
        }

        // ==================== PRODUCT FUNCTIONS ====================

        function addProductToTable(product) {
            const existingRow = $(`tr[data-id="${product.id}"]`);

            if (existingRow.length) {
                // Update existing row
                const qtyInput = existingRow.find('.quantity');
                const currentQty = parseInt(qtyInput.val()) || 0;
                qtyInput.val(currentQty + product.quantity);

                const price = parseFloat(existingRow.find('.price').text());
                existingRow.find('.total_amount').val((currentQty + product.quantity) * price);
            } else {
                // Add new row
                const rowHtml = `
        <tr data-id="${product.id}">
            <td>${product.code_id || product.id}</td>
            <td>${product.name}</td>
            <td>${product.unit || 'N/A'}</td>
            <td>
                <input type="number" name="products[${product.id}][quantity]" 
                       class="form-control quantity" value="${product.quantity}" min="1">
            </td>
            <td class="price">
                <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                <input type="hidden" name="products[${product.id}][name]" value="${product.name}">
                <input type="hidden" name="products[${product.id}][unit]" value="${product.unit || ''}">
                <input type="hidden" name="products[${product.id}][price]" value="${product.price}">
                ${product.price}
            </td>
            <td>
                <input type="text" name="products[${product.id}][total_amount]" 
                       class="form-control total_amount" value="${(product.quantity * product.price).toFixed(2)}" readonly>
            </td>
            <td>
                <button class="btn btn-danger btn-sm delete"><i class="bx bx-trash"></i></button>
            </td>
        </tr>`;

                $('.addMoreProduct').append(rowHtml);
            }

            calculateTotals();
        }

        function handleSearchInput() {
            const query = $(this).val().toLowerCase();

            if (query.length < 1) {
                $('#suggestions').hide();
                return;
            }

            $.ajax({
                url: '{{ route('cashier.product.search') }}',
                method: 'GET',
                data: {
                    term: query
                },
                success: function(response) {
                    if (response.length === 0) {
                        $('#suggestions').html('<li class="list-group-item">No Products Found</li>').show();
                    } else {
                        const suggestions = response.map(product => `
                    <li class="list-group-item suggestion-item" 
                        data-id="${product.id}" 
                        data-name="${product.name}" 
                        data-code_id="${product.code_id}" 
                        data-unit="${product.unit}"
                        data-price="${product.sell_price}">
                        ${product.code_id} | ${product.name} | ${product.unit}
                    </li>`).join('');
                        $('#suggestions').html(suggestions).show();
                    }
                },
                error: function() {
                    $('#suggestions').html('<li class="list-group-item">Error fetching products</li>').show();
                }
            });
        }

        function handleSuggestionClick() {
            const productId = $(this).data('id');
            const codeId = $(this).data('code_id');
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');

            $('#modalProductId').val(productId);
            $('#modalCodeId').val(codeId);
            $('#modalProductName').val(productName);
            $('#modalProductPrice').val(productPrice);
            $('#modalQuantity').val(1);
            $('#modalProductNameDisplay').text(`${codeId} - ${productName}`);
            $('#quantityModal').modal('show');
            $('#searchBar').val('');
            $('#suggestions').hide();
        }

        function addProductFromModal() {
            const productId = $('#modalProductId').val();
            const codeId = $('#modalCodeId').val();
            const productName = $('#modalProductName').val();
            const productPrice = $('#modalProductPrice').val();
            const quantity = parseInt($('#modalQuantity').val()) || 1;

            const product = {
                id: productId,
                code_id: codeId,
                name: productName,
                price: parseFloat(productPrice),
                quantity: quantity,
                unit: $('#modalProductUnit').val() || 'N/A'
            };

            addProductToTable(product);
            $('#quantityModal').modal('hide');
        }

        function handleQuantityChange() {
            const tr = $(this).closest('tr');
            let quantity = parseFloat($(this).val()) || 0;

            if (quantity < 1) {
                quantity = 1;
                $(this).val(1);
            }

            const price = parseFloat(tr.find('.price').text());
            tr.find('.total_amount').val((quantity * price).toFixed(2));
            calculateTotals();
        }

        function handleDeleteClick(e) {
            e.preventDefault();
            const row = $(this).closest('tr');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            $('#confirmDelete').off('click').on('click', function() {
                row.remove();
                calculateTotals();
                deleteModal.hide();
            });

            deleteModal.show();
        }

        // ==================== ORDER CALCULATIONS ====================

        function calculateTotals() {
            let subtotal = 0;
            $('.total_amount').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            // Add delivery fee if checked
            if ($('#deliveryCheckbox').is(':checked')) {
                subtotal += parseFloat($('#deliveryFee').val()) || 0;
            }

            // Calculate discount
            let discountAmount = 0;
            const discountType = $('#discountType').val();

            if (discountType === 'senior/pwd') {
                discountAmount = subtotal * 0.20;
                $('#discountPercentageInput').val(20);
            } else if (discountType === 'custom') {
                const customValue = parseFloat($('#customDiscountValue').val()) || 0;
                const customType = $('#customDiscountType').val();

                if (customType === 'percent') {
                    discountAmount = subtotal * (customValue / 100);
                    $('#discountPercentageInput').val(customValue);
                } else {
                    discountAmount = customValue;
                    $('#discountPercentageInput').val(0);
                }
            }

            $('#discountInput').val(discountAmount.toFixed(2));
            const finalTotal = subtotal - discountAmount;

            // Update displays
            $('#subtotalDisplay').text(subtotal.toFixed(2));
            $('#total').text(finalTotal.toFixed(2));
            $('#finalAmountInput').val(finalTotal.toFixed(2));

            // Update balance
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            $('#balance').val((paidAmount - finalTotal).toFixed(2));
        }

        // ==================== FORM CONTROLS ====================

        function handleDiscountTypeChange(select) {
            const $select = $(select);
            const customDiscountContainer = $('#customDiscountContainer');

            if ($select.val() === 'custom') {
                customDiscountContainer.slideDown();
                $('#customDiscountValue').val('');
                $('#customDiscountType').val('percent');
            } else {
                customDiscountContainer.slideUp();
            }

            calculateTotals();
        }

        function handleDeliveryChange() {
            if ($(this).is(':checked')) {
                $('#customerAddressContainer, #deliveryFeeContainer').slideDown();
                updateCustomerAddress();
            } else {
                $('#customerAddressContainer, #deliveryFeeContainer').slideUp();
                $('#customerAddress, #deliveryFee').val('');
            }
            updateDeliveryStatus(this);
            calculateTotals();
        }

        function updateDeliveryStatus(checkbox) {
            const $checkbox = $(checkbox);
            const deliveryStatus = $('#deliveryStatus');
            const hiddenInput = $('input[name="delivery"]');

            if ($checkbox.is(':checked')) {
                hiddenInput.val('YES');
                deliveryStatus.text('YES');
            } else {
                hiddenInput.val('NO');
                deliveryStatus.text('NO');
            }
        }

        function handleCustomerChange() {
            const $select = $(this);
            $('#clearCustomer').toggle($select.val() !== "");

            if ($select.val() === "") {
                $('#deliveryCheckbox').prop('disabled', true).prop('checked', false);
                handleDeliveryChange();
            } else {
                $('#deliveryCheckbox').prop('disabled', false);
                if ($('#deliveryCheckbox').is(':checked')) {
                    updateCustomerAddress();
                }
            }
        }

        function updateCustomerAddress() {
            const address = $("#customerSelect option:selected").data("address") || "";
            $("#customerAddress").val(address);
        }

        function handlePaymentChange() {
            const total = parseFloat($('#finalAmountInput').val()) || 0;
            const paidAmount = parseFloat($(this).val().replace(/,/g, '')) || 0;
            $('#balance').val((paidAmount - total).toFixed(2));
        }

        // ==================== FORM SUBMISSION ====================


        $(document).on('click', '#cancelReservationBtn', function() {
            if (confirm('Are you sure you want to cancel this reservation?')) {
                $.ajax({
                    url: '{{ route('cashier.cancelReservation') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customer_id: $('#customerSelect').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            // Remove all reserved products from the table
                            $('.addMoreProduct').empty();
                            // Reset totals
                            calculateTotals();
                            // Remove the cancel button
                            $('#cancelReservationBtn').remove();
                            // Optionally reload the page to clear everything
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error cancelling reservation');
                    }
                });
            }
        });

        function handleSubmit() {
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            const total = parseFloat($('#finalAmountInput').val()) || 0;

            if (paidAmount < total) {
                alert('Paid amount is less than total amount. Please collect full payment.');
                return;
            }

            $('#modalPaidAmount').text(paidAmount.toFixed(2));
            $('#modalChange').text((paidAmount - total).toFixed(2));

            const saveModal = new bootstrap.Modal(document.getElementById('saveConfirmationModal'));
            saveModal.show();
        }

        function submitForm(shouldPrint) {
            const saveModal = bootstrap.Modal.getInstance(document.getElementById('saveConfirmationModal'));
            saveModal.hide();

            $.ajax({
                url: $('#salesForm').attr('action'),
                method: 'POST',
                data: $('#salesForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        if (shouldPrint && response.printReceipt) {
                            printReceipt(response.printReceipt);
                        }
                        setTimeout(() => {
                            window.location.href = response.redirect || '/cashier/dashboard';
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) toastr.error(response.message);
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }
                    } catch (e) {
                        toastr.error('Error saving sale. Please try again.');
                    }
                }
            });
        }

        function printReceipt(receiptId) {
            const windowFeatures =
                'width=800,height=600,left=100,top=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes';
            const printWindow = window.open('/cashier/receipts2/' + receiptId, 'ReceiptPrint', windowFeatures);

            if (!printWindow) {
                toastr.warning('Popup was blocked. Please enable popups for this site to print receipts.');
                return;
            }

            printWindow.onload = function() {
                try {
                    printWindow.focus();
                    setTimeout(() => {
                        printWindow.print();
                        setTimeout(() => printWindow.close(), 2000);
                    }, 500);
                } catch (e) {
                    console.log("Automatic printing blocked by browser");
                    setTimeout(() => printWindow.close(), 5000);
                }
            };
        }
    </script>
</body>

</html>
