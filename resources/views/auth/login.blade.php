<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>Log In</title>
</head>

<body>

    {{-- Main container for login page --}}
    <div class="login-container">

        {{-- Left section for promotional content --}}
        <div class="login-left">
            <div class="promo-content">
                <img src="{{ asset('assets/images/kc.png') }}" alt="Logo" class="promo-image">
                <h2>KC PRIME SUITE</h2>
                <p>POS AND ENTERPRISE MANAGEMENT SYSTEM</p>
            </div>
        </div>

        {{-- Right section for login form --}}
        <div class="login-right">
            <div class="login-box">
                <h2>Welcome Back</h2>
                <p>Please Enter your Details</p>

                @if (session('login_error'))
                    <div class="error-message">
                        {{ session('login_error') }}
                    </div>
                @endif

                {{-- Login form with username and password fields --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Username input field --}}
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />

                    {{-- Password input field --}}
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="password-container">
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        {{-- Toggle password visibility --}}
                        <span id="togglePassword" class="toggle-password">
                            <i class="bx bx-show-alt"></i>
                        </span>
                    </div>

                    {{-- Sign in button --}}
                    <button type="submit" class="login-btn">{{ __('Log in') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/login.js') }}"></script>
</body>

</html>
