@extends('customer.auth.layout2')

@section('title', 'Products')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container pt-5">
        <div class="d-flex align-items-center mb-5">
            <div class="p-2 me-3 border border-2 rounded-circle" style="width: 44px; height: 44px;">
                <i class="bx bx-box text-primary fs-4 d-flex justify-content-center align-items-center"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="m-0 position-relative pb-2">
                    Our Products
                </h2>
            </div>
            <a href="{{ route('customer.home') }}" class="btn btn-sm btn-outline-primary ms-3">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>

        <!-- Category Filter -->
        <div class="category-filter mb-4">
            <div class="category-btn active" data-category="all">All Products</div>
            @foreach($categories as $category)
                <div class="category-btn" data-category="{{ $category }}">
                    {{ $category }}
                </div>
            @endforeach
        </div>

        <!-- Product Grid -->
        <div class="row min-vh-50" id="product-grid">
            @if($products->isEmpty())
                <div class="col-12 d-flex flex-column align-items-center justify-content-center py-5">
                    <img src="{{ asset('images/empty-box.png') }}" alt="No Products" style="width: 120px; opacity: 0.6;">
                    <h5 class="text-muted mt-3">No products available.</h5>
                </div>
            @else
                @foreach($products as $product)
                    <div class="col-md-4 col-sm-6 mb-4 product-item" data-category="{{ $product->category->name ?? '' }}">
                        <div class="card product-card" data-bs-toggle="modal" data-bs-target="#productModal" 
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->sell_price }}" 
                            data-quantity="{{ $product->quantity }}"
                            data-unit="{{ $product->unit ? $product->unit->name : 'Not Available' }}"
                            data-description="{{ $product->description ?? 'Not Available'}}"
                            data-image="{{ $product->imageUrl }}">
                            
                            <img src="{{ $product->imageUrl }}" 
                                class="card-img-top product-img" 
                                alt="{{ $product->name }}"
                                onerror="this.src='{{ asset('images/Product Image Not Available.jpg') }}'">
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                
                                <!-- Stock Availability -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Available Stock:</span>
                                    <strong class="{{ $product->quantity <= $product->reorder_level ? 'text-danger' : 'text-success' }}">
                                        {{ $product->quantity }} {{ $product->unit ? $product->unit->name : '' }}
                                        @if($product->quantity <= $product->reorder_level)
                                            <i class="bx bx-error-alt"></i>
                                        @endif
                                    </strong>
                                </div>
                                
                                <p class="fw-bold text-end text-primary mb-0">
                                    ₱{{ number_format($product->sell_price, 2) }} 
                                    <small class="text-muted">per {{ $product->unit ? $product->unit->name : 'unit' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div id="no-products-message" class="col-12 d-none text-center py-5">
                    <img src="{{ asset('images/Empty Box.png') }}" alt="No Products" style="width: 120px; opacity: 0.6;">
                    <h5 class="text-muted mt-3">No products available.</h5>
                </div>
            @endif
        </div>  
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold fs-4" id="productModalLabel">
                        <i class="bx bx-detail me-2"></i> Product Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Image Column -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="position-relative">
                                <div class="ratio ratio-1x1 bg-light rounded-3 overflow-hidden shadow-sm">
                                    <img src="" id="modalProductImage" 
                                        class="img-fluid object-fit-cover p-3"
                                        onerror="this.src='{{ asset('images/Product Image Not Available.jpg') }}'"
                                        alt="Product Image">
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-info text-dark fs-6 mb-2">
                                        <i class="bx bx-category"></i>
                                        <span id="modalProductCategory"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Details Column -->
                        <div class="col-md-6">
                            <div class="d-flex flex-column h-100">
                                <!-- Product Name -->
                                <h3 id="modalProductName" class="fw-bold mb-3 text-primary"></h3>
                                
                                <!-- Description -->
                                <div class="card bg-light border-0 mb-4 flex-grow-1">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2">Description</h6>
                                        <p id="modalProductDescription" class="card-text"></p>
                                    </div>
                                </div>
                                
                                <!-- Price & Stock -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center h-100">
                                                    <div>
                                                        <span class="text-muted small">Unit Price:</span>
                                                        <div class="d-flex align-items-center">
                                                            <span id="modalProductPrice" class="fs-3 fw-bold text-primary"></span>
                                                            <small class="text-muted ms-1">/ <span id="modalProductUnit">pc</span></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column align-items-end">
                                                    <span class="text-muted small">Available Stock:</span>
                                                    <span id="modalProductStock" class="fs-3 fw-bold"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reserve Form -->
                                @auth('customer')
                                <form method="POST" action="{{ route('customer.reserve.product') }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="product_id" id="modalProductId">
                                    
                                    <div class="mb-3">
                                        <label for="modalProductQuantity" class="form-label fw-medium">Quantity</label>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary decrement-qty" type="button">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" id="modalProductQuantity" 
                                                class="form-control text-center" value="1" min="1" required>
                                            <button class="btn btn-outline-secondary increment-qty" type="button">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                        <i class="bx bx-cart-add me-2"></i> Reserve Now
                                    </button>
                                </form>
                                @else
                                    <a href="{{ route('customer.login') }}" class="btn btn-outline-primary w-100 py-3 fw-bold">
                                        <i class="bx bx-log-in-circle me-2"></i> Login to Reserve
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Modal Styling */
        #productModal .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        
        #productModal .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }
        
        #productModal .modal-body {
            padding: 2rem;
        }
        
        #productModal .btn-close {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        #productModal .btn-close:hover {
            opacity: 1;
        }
        
        /* Quantity Input Styling */
        #productModal .input-group {
            max-width: 150px;
        }
        
        #productModal .input-group button {
            width: 40px;
        }
        
        #productModal .input-group input {
            border-left: none;
            border-right: none;
            font-weight: 600;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #productModal .modal-body {
                padding: 1.5rem;
            }
            
            #productModal .modal-header {
                padding: 1rem 1.5rem;
            }
        }
    </style>

    {{-- PRODUCT FILTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryButtons = document.querySelectorAll('.category-btn');
            const productItems = document.querySelectorAll('.product-item');
            const noProductsMessage = document.getElementById('no-products-message');
    
            categoryButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const selectedCategory = btn.getAttribute('data-category');
                    
                    let visibleCount = 0;
    
                    productItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        const showItem = selectedCategory === 'all' || itemCategory === selectedCategory;
                        
                        item.style.display = showItem ? 'block' : 'none';
                        if (showItem) visibleCount++;
                    });
    
                    // Toggle no-products message
                    if (visibleCount === 0) {
                        noProductsMessage.classList.remove('d-none');
                    } else {
                        noProductsMessage.classList.add('d-none');
                    }
    
                    // Active state toggle
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        });
    </script>

    MODAL & RESERVE PRODUCT FUNCTION
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity Input Handling
            const quantityInput = document.getElementById('productQuantity');
            const stockElement = document.getElementById('modalProductStock');
            const decrementBtn = document.getElementById('decrementQty');
            const incrementBtn = document.getElementById('incrementQty');
            const productModal = document.getElementById('productModal');

            // Update hidden quantity and validate
            const updateQuantity = () => {
                const currentStock = parseInt(stockElement?.textContent || '0');
                let quantity = parseInt(quantityInput.value) || 1;
                quantity = Math.max(1, Math.min(currentStock, quantity));
                
                quantityInput.value = quantity;
                document.getElementById('hiddenProductQuantity').value = quantity;
                
                // Update total price display
                const basePrice = parseFloat(productModal.dataset.basePrice || 0);
                const totalPrice = basePrice * quantity;
                document.getElementById('modalProductPrice').textContent = '₱' + totalPrice.toFixed(2);
            };

            // Quantity button handlers
            if (quantityInput) {
                quantityInput.addEventListener('input', updateQuantity);
                quantityInput.addEventListener('change', updateQuantity);
                
                incrementBtn?.addEventListener('click', () => {
                    quantityInput.stepUp();
                    updateQuantity();
                });
                
                decrementBtn?.addEventListener('click', () => {
                    quantityInput.stepDown();
                    updateQuantity();
                });
            }

            // Modal show event
            productModal?.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                
                // Set form fields
                document.getElementById('hiddenProductId').value = button.dataset.id;
                document.getElementById('hiddenProductQuantity').value = 1;
                
                // Set product data
                productModal.dataset.basePrice = button.dataset.price;
                quantityInput.max = button.dataset.quantity;
                quantityInput.value = 1;

                // Update UI
                document.getElementById('modalProductName').textContent = button.dataset.name;
                document.getElementById('modalProductPrice').textContent = '₱' + parseFloat(button.dataset.price).toFixed(2);
                document.getElementById('modalProductDescription').textContent = button.dataset.description;
                document.getElementById('modalProductCategory').textContent = button.dataset.category;
                document.getElementById('unitPrice').textContent = parseFloat(button.dataset.price).toFixed(2);
                document.getElementById('modalProductUnit').textContent = button.dataset.unit || 'Not Available';
                
                // Set image
                const img = document.getElementById('modalProductImage');
                img.src = button.dataset.image || '{{ asset("images/Product Image Not Available.jpg") }}';
                
                // Set stock info
                const stockElement = document.getElementById('modalProductStock');
                const quantity = parseInt(button.dataset.quantity);
                stockElement.textContent = quantity + ' ' + (button.dataset.unit || '');

                // Clear existing warning icon
                const existingIcon = stockElement.querySelector('i');
                if (existingIcon) existingIcon.remove();

                // Add warning if stock is low
                if (quantity <= 5) {
                    const warningIcon = document.createElement('i');
                    warningIcon.className = 'bx bx-error-alt text-danger ms-1';
                    stockElement.appendChild(warningIcon);
                }
                
                // Initialize quantity
                updateQuantity();
            });

            // Form submission
            document.getElementById('reservationForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const form = this;
                const button = document.getElementById('reserveProduct');
                const spinner = document.getElementById('spinner');
                const submitText = document.getElementById('submitText');
                
                // Show loading state
                button.disabled = true;
                submitText.classList.add('d-none');
                spinner.classList.remove('d-none');
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new FormData(form)
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Reservation failed');
                    }
                    
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#28a745",
                    }).showToast();
                    
                    // Close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }, 1500);
                    
                } catch (error) {
                    Toastify({
                        text: error.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                    }).showToast();
                } finally {
                    button.disabled = false;
                    submitText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quantity increment/decrement
            document.querySelectorAll('.increment-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('input[type=number]');
                    input.stepUp();
                    updateMaxQuantity(input);
                });
            });
            
            document.querySelectorAll('.decrement-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('input[type=number]');
                    input.stepDown();
                    updateMaxQuantity(input);
                });
            });
            
            function updateMaxQuantity(input) {
                const max = parseInt(input.getAttribute('max'));
                if (parseInt(input.value) > max) {
                    input.value = max;
                }
            }
            
            // Modal initialization
            const productModal = document.getElementById('productModal');
            if (productModal) {
                productModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const productData = {
                        id: button.getAttribute('data-id'),
                        name: button.getAttribute('data-name'),
                        price: button.getAttribute('data-price'),
                        unit: button.getAttribute('data-unit'),
                        quantity: button.getAttribute('data-quantity'),
                        description: button.getAttribute('data-description'),
                        image: button.getAttribute('data-image'),
                        category: button.getAttribute('data-category')
                    };

                    // Populate modal fields
                    document.getElementById('modalProductId').value = productData.id;
                    document.getElementById('modalProductQuantity').max = productData.quantity;
                    document.getElementById('modalProductQuantity').value = 1;
                    document.getElementById('modalProductName').textContent = productData.name;
                    document.getElementById('modalProductPrice').textContent = '₱' + parseFloat(productData.price).toFixed(2);
                    document.getElementById('modalProductUnit').textContent = productData.unit || 'pc';
                    document.getElementById('modalProductDescription').textContent = productData.description || 'No description available';
                    document.getElementById('modalProductCategory').textContent = productData.category || 'Uncategorized';
                    document.getElementById('modalProductImage').src = productData.image || '{{ asset("images/Product Image Not Available.jpg") }}';
                    document.getElementById('modalProductStock').textContent = productData.quantity + ' ' + (productData.unit || '');
                    
                    // Stock level indicator
                    const stockElement = document.getElementById('modalProductStock');
                    stockElement.className = 'fs-3 fw-bold ' + 
                        (parseInt(productData.quantity) <= 5 ? 'text-danger' : 'text-success');
                    
                    if (parseInt(productData.quantity) <= 5) {
                        const warningIcon = document.createElement('i');
                        warningIcon.className = 'bx bx-error-alt ms-2';
                        stockElement.appendChild(warningIcon);
                    }
                });
            }
        });
    </script>
      
@endsection