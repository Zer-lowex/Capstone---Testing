<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-login {
            padding: 10px;
            font-weight: 600;
        }
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        @media (max-width: 576px) {
            body {
                padding: 15px;
                align-items: flex-start;
                padding-top: 30px; /* Extra space at top */
            }
            .login-container {
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
            }
            .login-header {
                margin-bottom: 20px;
            }
            .login-header h2 {
                font-size: 1.3rem;
            }
            .btn-login {
                padding: 8px;
            }
            .form-check-label {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('customer.home') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Home
                </a>
            </div>
            <h2>Customer Login</h2>
            <p class="text-muted">Please enter your credentials</p>
        </div>
        
        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="loginForm" method="POST" action="{{ route('customer.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="loginUsername" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                    id="loginUsername" name="username" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="loginPassword" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-login">Login</button>
            
            <div class="forgot-password">
                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
            </div>
        </form>
        
        <div class="mt-4 text-center">
            <p class="text-muted">Don't have an account? <a href="{{ route('customer.register') }}" class="text-decoration-none">Register here</a></p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>