@extends('owner.layout-2')

@section('title', 'Profile')

@section('content')

    <h1 class="title">Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="{{ route('owner.dashboard') }}">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Profile</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Owner</h4>
                        <div class="d-flex position-relative">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#updateProfileModal">
                                <i class="bx bx-edit"></i>
                            </button>
                        </div>
                    </div>

                    <div class="info-data">
                        <!-- Modern Profile Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Username</h5>
                                        <p class="card-text">{{ $user->username }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">First Name</h5>
                                        <p class="card-text">{{ $user->first_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Last Name</h5>
                                        <p class="card-text">{{ $user->last_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Email</h5>
                                        <p class="card-text">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Phone Number</h5>
                                        <p class="card-text">{{ $user->phone ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Address</h5>
                                        <p class="card-text">{{ $user->address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <form method="POST" action="{{ route('owner.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="form-control" type="text" name="username" value="{{ $user->username }}" required autofocus />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="form-control" type="text" name="first_name" value="{{ $user->first_name }}" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="form-control" type="text" name="last_name" value="{{ $user->last_name }}" required autofocus />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" value="{{ $user->email }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="form-control" type="text" name="address" value="{{ old('address', $user->address) }}" />
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

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Change Password</h4>
                    </div>
    
                    <div class="data">
                        <form method="POST" action="{{ route('owner.updatePass') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                                @error('new_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                                @error('new_password_confirmation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
    
                            <button type="submit" class="btn btn-primary float-end">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
