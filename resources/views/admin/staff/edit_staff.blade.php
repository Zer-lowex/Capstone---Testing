@extends('admin.layout')

@section('title', 'Edit Staff')

@section('content')

<h1 class="title">Edit Staff</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Edit Staff</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h4>Edit Staff
                            <a href="{{ url('/admin/staff') }}" class="btn btn-primary float-end">Back</a>
                        </h4> 
                    </div>
                    <div class="card-body modern-card-body">
                        <form action="{{ route('staff.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}" />
                                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Usertype</label>
                                <select name="usertype" class="form-control rounded-3">
                                    <option value="" disabled>Select User Type</option>
                                    <option value="Staff" {{ $user->usertype == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Cashier" {{ $user->usertype == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="Driver" {{ $user->usertype == 'Driver' ? 'selected' : '' }}>Driver</option>
                                </select>
                                @error('usertype') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" />
                                @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" />
                                @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" value="{{ $user->email }}" />
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" />
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address }}" />
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Staff</button>
                            </div>
                        </form>
                        
                        <form action="{{ route('staff.resetPassword', $user->id) }}" method="POST" id="resetPasswordForm">
                            @csrf
                            @method('PUT')
    
                            <input type="hidden" name="password" value="Password123">
    
                            <div class="mb-3">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                    Reset Password
                                </button>
                            </div>

                            <div class="mt-3">
                                <p class="text-muted small">
                                    Note: The default password for the staff account is <strong>Password123</strong>.
                                </p>
                            </div>
                        </form>

                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="confirmResetModalLabel">Confirm Password Reset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                Are you sure you want to reset this staff's password to the default password?
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmResetButton">Yes, Reset Password</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirmResetButton').addEventListener('click', function () {
            document.getElementById('resetPasswordForm').submit();
        });
    </script>
@endsection
