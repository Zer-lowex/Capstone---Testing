@extends('admin.layout-2')

@section('title', 'Website Products')

@section('content')
    <div class="container pt-5">
        <!-- Header Section -->
        <div class="d-flex align-items-center mb-5">
            <div class="p-2 me-3 border border-2 rounded-circle" style="width: 44px; height: 44px;">
                <i class="bx bx-box text-primary fs-4 d-flex justify-content-center align-items-center"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="m-0 position-relative pb-2">
                    Product List
                </h2>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="category-filter mb-4">
            <div class="category-btn active" data-category="all">All Products</div>
            @foreach ($categories as $id => $name)
                <div class="category-btn" data-category="{{ $id }}">
                    {{ $name }}
                </div>
            @endforeach
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" id="product-search" class="form-control" placeholder="Search products...">
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row min-vh-50" id="product-grid">
            @if ($products->isEmpty())
                <div class="col-12 d-flex flex-column align-items-center justify-content-center py-5">
                    <img src="{{ asset('images/Empty Box.png') }}" alt="No Products" style="width: 120px; opacity: 0.6;">
                    <h5 class="text-muted mt-3">No products available.</h5>
                </div>
            @else
                @foreach ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item"
                        data-category="{{ $product->category_id ?? '' }}" data-name="{{ strtolower($product->name) }}"
                        data-price="{{ $product->sell_price }}">

                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/Product Image Not Available.jpg') }}"
                                    class="card-img-top product-img" alt="{{ $product->name }}"
                                    style="height: 200px; object-fit: cover;">

                                @if ($product->quantity <= $product->reorder_level)
                                    <span
                                        class="position-absolute top-0 end-0 bg-danger text-white small px-2 py-1 rounded-bl">
                                        <i class="bx bx-error-circle me-1"></i> Low Stock
                                    </span>
                                @endif

                                @if ($product->expiration_date && $product->expiration_date < now()->addDays(30))
                                    <span
                                        class="position-absolute top-0 start-0 bg-warning text-dark small px-2 py-1 rounded-br">
                                        <i class="bx bx-time me-1"></i> Expiring Soon
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <h5 class="card-title mb-1">{{ $product->name }}</h5>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @if ($product->category)
                                            <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                                        @endif
                                        @if ($product->supplier)
                                            <span class="badge bg-light text-dark">{{ $product->supplier->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-muted small mb-3 flex-grow-1">
                                    {{ Str::limit($product->description ?? 'No description available', 100) }}
                                </p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="text-muted small">Stock:</span>
                                            <strong
                                                class="{{ $product->quantity <= $product->reorder_level ? 'text-danger' : 'text-success' }}">
                                                {{ $product->quantity }}
                                                {{ $product->unit ? $product->unit->name : 'units' }}
                                            </strong>
                                        </div>
                                        <span class="fw-bold text-primary">
                                            ₱{{ number_format($product->sell_price, 2) }}
                                        </span>
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#productModal" data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-image="{{ $product->image ? asset('images/' . $product->image) : asset('images/Product Image Not Available.jpg') }}"
                                            data-product-category="{{ $product->category->name ?? 'N/A' }}"
                                            data-product-unit="{{ $product->unit ? $product->unit->name : 'N/A' }}"
                                            data-product-description="{{ $product->description ?? 'No description available' }}"
                                            data-product-stock="{{ $product->quantity }}"
                                            data-product-price="{{ $product->sell_price }}">
                                            <i class="bx bx-show me-1"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="saveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Product Image Column -->
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <img id="modalProductImage" src="" class="img-fluid rounded"
                                    style="max-height: 300px; width: 100%; object-fit: contain;">

                                <div class="text-center mt-3">
                                    <button class="btn btn-sm btn-outline-secondary" id="uploadPhotoBtn">
                                        <i class="bx bx-upload me-1"></i> Upload New Photo
                                    </button>
                                    <input type="file" id="productImageUpload" style="display: none;"
                                        accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Column -->
                        <div class="col-md-6">
                            <h4 id="modalProductName" class="mb-3"></h4>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="bx bx-category"></i>
                                        <span id="modalProductCategory"></span>
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bx bx-ruler"></i>
                                        <span id="modalProductUnit"></span>
                                    </span>
                                </div>

                                <div class="card bg-light p-3 mb-3">
                                    <h6 class="mb-2">Description</h6>
                                    <p id="modalProductDescription" class="mb-0"></p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="text-muted">Current Stock:</span>
                                        <strong id="modalProductStock" class="ms-1"></strong>
                                    </div>
                                    <div>
                                        <span class="text-muted">Price:</span>
                                        <strong id="modalProductPrice" class="ms-1 text-primary"></strong>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="productDescriptionEdit" placeholder="Edit description" style="height: 100px"></textarea>
                                    <label for="productDescriptionEdit">Edit Description</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="saveDescriptionBtn">
                        <i class="bx bx-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .category-filter {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 6px 12px;
            border-radius: 20px;
            background: #f1f1f1;
            color: #555;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
        }

        .category-btn:hover,
        .category-btn.active {
            background: #0d6efd;
            color: white;
        }

        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Filter
            const categoryBtns = document.querySelectorAll('.category-btn');
            const productItems = document.querySelectorAll('.product-item');

            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.dataset.category;
                    filterProducts();
                });
            });

            // Search Functionality
            const searchInput = document.getElementById('product-search');
            searchInput.addEventListener('input', filterProducts);

            function filterProducts() {
                const activeCategory = document.querySelector('.category-btn.active').dataset.category;
                const searchTerm = searchInput.value.toLowerCase();

                let visibleCount = 0;

                productItems.forEach(item => {
                    const matchesCategory = activeCategory === 'all' || item.dataset.category ===
                        activeCategory;
                    const matchesSearch = item.dataset.name.includes(searchTerm) ||
                        item.dataset.price.toString().includes(searchTerm);

                    if (matchesCategory && matchesSearch) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show empty state if no products match
                const emptyState = document.getElementById('no-products-message');
                if (emptyState) {
                    emptyState.classList.toggle('d-none', visibleCount > 0);
                }
            }
        });
    </script>

    {{-- PRODUCT MODAL --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selectedProductId = null;

            const productModal = document.getElementById("productModal");
            const modalProductImage = document.getElementById("modalProductImage");
            const modalProductName = document.getElementById("modalProductName");
            const modalProductCategory = document.getElementById("modalProductCategory");
            const modalProductUnit = document.getElementById("modalProductUnit");
            const modalProductDescription = document.getElementById("modalProductDescription");
            const productDescriptionEdit = document.getElementById("productDescriptionEdit");
            const modalProductStock = document.getElementById("modalProductStock");
            const modalProductPrice = document.getElementById("modalProductPrice");

            // Fill modal with data
            const viewButtons = document.querySelectorAll("[data-bs-target='#productModal']");
            viewButtons.forEach(button => {
                button.addEventListener("click", function() {
                    selectedProductId = this.dataset.productId;
                    modalProductImage.src = this.dataset.productImage;
                    modalProductName.textContent = this.dataset.productName;
                    modalProductCategory.textContent = this.dataset.productCategory;
                    modalProductUnit.textContent = this.dataset.productUnit;
                    modalProductDescription.textContent = this.dataset.productDescription;
                    productDescriptionEdit.value = this.dataset.productDescription;
                    modalProductStock.textContent = this.dataset.productStock;
                    modalProductPrice.textContent = formatPrice(this.dataset.productPrice);
                });
            });

            function formatPrice(price) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(Number(price));
            }

            // Trigger file input click
            document.getElementById("uploadPhotoBtn").addEventListener("click", function() {
                document.getElementById("productImageUpload").click();
            });

            // Image upload
            document.getElementById("productImageUpload").addEventListener("change", function(e) {
                const file = e.target.files[0];
                if (!file || !selectedProductId) return;

                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validImageTypes.includes(file.type)) {
                    showToast('Only JPG, PNG, GIF, or WEBP images are allowed', 'error');
                    e.target.value = ''; // Reset file input
                    return;
                }

                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    showToast('Image size must be less than 5MB', 'error');
                    e.target.value = ''; // Reset file input
                    return;
                }

                const formData = new FormData();
                formData.append("product_id", selectedProductId);
                formData.append("image", file);

                // Show loading state
                const uploadBtn = document.getElementById("uploadPhotoBtn");
                uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Uploading...';
                uploadBtn.disabled = true;

                fetch("{{ route('admin.site.update-image') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update image preview
                            modalProductImage.src = data.image_url + '?t=' + Date.now();

                            // Update product card image if visible
                            const productCardImg = document.querySelector(
                                `[data-product-id="${selectedProductId}"] .product-img`);
                            if (productCardImg) {
                                productCardImg.src = data.image_url + '?t=' + Date.now();
                            }

                            showToast(data.message, 'success');

                            // Close the modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'productModal'));
                            if (modal) {
                                modal.hide();
                            }

                            // Refresh the page after 1.5 seconds (to show the toast first)
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error("Upload error:", error);
                        let errorMessage = "Upload failed";
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).join('<br>');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        showToast(errorMessage, 'error');
                    })
                    .finally(() => {
                        uploadBtn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload New Photo';
                        uploadBtn.disabled = false;
                        e.target.value = ''; // Reset file input
                    });
            });

            // Description update
            document.getElementById("saveDescriptionBtn").addEventListener("click", function() {
                const description = productDescriptionEdit.value.trim();
                if (!description || !selectedProductId) return;

                // Show loading state
                const saveBtn = this;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
                saveBtn.disabled = true;

                fetch("{{ route('admin.site.update-description') }}", {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: selectedProductId,
                            description: description
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalProductDescription.textContent = description;
                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error("Description update failed:", error);
                        showToast("Unexpected error occurred while saving description.", 'error');
                    })
                    .finally(() => {
                        saveBtn.innerHTML = '<i class="bx bx-save me-1"></i> Save Changes';
                        saveBtn.disabled = false;
                    });
            });

            // Toast function (make sure this exists in your layout)
            function showToast(message, type = 'success') {
                // Implement your toast notification system here
                // Example using Bootstrap Toasts:
                const toastEl = document.createElement('div');
                toastEl.className =
                    `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

                document.body.appendChild(toastEl);
                const toast = new bootstrap.Toast(toastEl);
                toast.show();

                // Remove toast after hiding
                toastEl.addEventListener('hidden.bs.toast', function() {
                    toastEl.remove();
                });
            }
        });
    </script>

@endsection
