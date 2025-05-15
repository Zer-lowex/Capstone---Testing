@extends('customer.auth.layout2')

@section('title', 'Profile')

@section('content')
<div class="profile-container">
    <!-- Header Section -->
    <div class="profile-header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">My Profile</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Profile and Password Section -->
    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Personal Information</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                        <i class="bx bx-edit"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Profile updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Username</span>
                            <span class="info-value">{{ $customer->username }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $customer->first_name }} {{ $customer ->last_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $customer->email ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ $customer->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Address</span>
                            <span class="info-value">{{ $customer->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Password updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('customer.updatePass') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                        </div>
        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-lock-alt me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- My Reserved Products -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">My Reserved Products</h5>
        </div>
        <div class="card-body">
            @if($reservedProducts->isEmpty())
                <div class="alert alert-info mb-0">No reserved products found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Reserved On</th>
                                <th>Expires On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservedProducts as $reservation)
                            <tr>
                                <td>{{ $reservation->product->name }}</td>
                                <td>{{ $reservation->product->unit->name ?? 'N/A' }}</td>
                                <td>{{ $reservation->quantity }}</td>
                                <td>{{ $reservation->created_at->format('M d, Y') }}</td>
                                <td>{{ $reservation->expired_at->format('M d, Y') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger cancel-reservation" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            data-reservation-id="{{ $reservation->id }}">
                                        Cancel
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $reservedProducts->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Purchase History -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Purchase History</h5>
        </div>
        <div class="card-body">
            @if($purchases->isEmpty())
                <div class="alert alert-info mb-0">No purchase history found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->created_at->format('M d, Y') }}</td>
                                <td>{{ $sale->sale_items_count }} {{ Str::plural('item', $sale->sale_items_count) }}</td>
                                <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

    <!-- Modal for Updating Profile -->
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-black justify-content-center">
                    <h5 class="modal-title fw-bold" id="updateProfileModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="form-control" type="text" name="username" value="{{ $customer->username }}" required autofocus />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="form-control" type="text" name="first_name" value="{{ $customer->first_name }}" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="form-control" type="text" name="last_name" value="{{ $customer->last_name }}" required autofocus />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" value="{{ $customer->email }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone', $customer->phone) }}" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="form-control" type="text" name="address" value="{{ old('address', $customer->address) }}" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-3 text-end">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary text-white">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Reservation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this reservation?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Cancel Reservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const icon = this.querySelector('i');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('bx-hide');
                icon.classList.toggle('bx-show');
            });
        });
    </script>

    {{-- CANCEL RESERVATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // When any cancel button is clicked
            document.querySelectorAll('.cancel-reservation').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the reservation ID from data attribute
                    const reservationId = this.getAttribute('data-reservation-id');
                    
                    // Set the form action with the correct route and ID
                    const form = document.getElementById('deleteForm');
                    form.action = "{{ route('customer.delete.reserve', '') }}/" + reservationId;
                });
            });
        });
    </script>
    
@endsection
