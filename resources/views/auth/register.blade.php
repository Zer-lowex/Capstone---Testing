<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Register</title>
</head>

<body>
    {{-- Main container for register page --}}
    <div class="register-container">
        {{-- Left section for promotional content --}}
        <div class="register-left">
            <div class="promo-content">
                <img src="{{ asset('assets/images/kc.png') }}" alt="Logo" class="promo-image">
                <h2>KC PRIME SUITE</h2>
                <p>INTEGRATED POINT-OF-SALE WITH RESERVATION MANAGEMENT SYSTEM</p>
            </div>
        </div>

        {{-- Right section for register form --}}
        <div class="register-right">
            <div class="register-wrapper"> 
                <div class="register-box"> 
                    <h2>Create Account</h2>
                    <p class="subtitle">Please Enter your Details</p>

                    @if ($errors->any())
                        <div class="error-messageR">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Register form --}}
                    <form method="POST" action="{{ route('register') }}" class="register-form">
                        @csrf

                        <div class="form-row">
                            <!-- First Name -->
                            <div class="form-group">
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="form-row">
                            <!-- Mobile Number -->
                            <div class="form-group">
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- User Type -->
                            <div class="form-group">
                                <x-input-label for="usertype" :value="__('User Type')" />
                                <select id="usertype" name="usertype" class="block mt-1 w-full" required autofocus>
                                    <option value="" disabled selected>Select User Type</option>
                                    <option value="Admin" {{ old('usertype') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Owner" {{ old('usertype') == 'Owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="Staff" {{ old('usertype') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Cashier" {{ old('usertype') == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                                </select>
                                <x-input-error :messages="$errors->get('usertype')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required autofocus autocomplete="address" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="form-row">
                            <!-- Password -->
                            <div class="form-group">
                                <x-input-label for="password" :value="__('Password')" />
                                <div class="password-container">
                                    <x-text-input id="password" class="block mt-1 w-full"
                                                    type="password"
                                                    name="password"
                                                    required autocomplete="new-password" />
                                    <span id="togglePassword" class="toggle-password">
                                        <i class="bx bx-show-alt"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <div class="password-container">
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                    type="password"
                                                    name="password_confirmation" required autocomplete="new-password" />
                                    <span id="toggleConfirmPassword" class="toggle-password">
                                        <i class="bx bx-show-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <a class="text-link" href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </a>

                            <button type="submit" class="register-btn">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/login.js') }}"></script>
    <script>
        // Add password toggle functionality for both password fields
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
            const password = document.querySelector('#password');
            const confirmPassword = document.querySelector('#password_confirmation');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bx-hide');
                    this.querySelector('i').classList.toggle('bx-show-alt');
                });
            }
            
            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPassword.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bx-hide');
                    this.querySelector('i').classList.toggle('bx-show-alt');
                });
            }
        });
    </script>
</body>

</html>