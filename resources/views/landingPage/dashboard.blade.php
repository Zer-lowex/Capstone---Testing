<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/kc.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/landingPage.css') }}">
    <title>KC Prime Enterprises</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        {{-- @include('landingPage.partials.sidebar') --}}
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        @include('landingPage.partials.navbar')
        <!-- NAVBAR -->

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Customer Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <form id="loginForm" method="POST" action="{{ route('customer.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="loginUsername" class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                    id="loginUsername" name="username" value="{{ old('username') }}" required>
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
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="#" id="showRegister">Register</a></p>
                            <p><a href="#">Forgot password?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Create Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registerForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email (optional)</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="#" id="showLogin">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN -->
        <main>
            <section class="hero-section d-flex align-items-center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7 p-0">
                            <div class="hero-image h-100 w-100"></div>
                        </div>
            
                        <!-- Text Side -->
                        <div class="col-md-5 d-flex align-items-center justify-content-center text-center text-content px-4">
                            <div>
                                <h1 class="display-5 fw-bold">Welcome to KC Prime Enterprises</h1>
                                <p class="lead">Your trusted partner in quality construction and hardware supplies.</p>
                                <a href="{{ url('/products') }}" class="btn btn-primary btn-lg mt-3">View Available Products</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>            
            
            <section class="features py-5">
                <div class="container">
                    <h2 class="text-center mb-4">Why Choose Us?</h2>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="bx bx-box bx-lg mb-3"></i>
                                <h4>Wide Selection</h4>
                                <p>We offer a wide range of construction and hardware supplies to meet all your needs.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="bx bxs-truck bx-lg mb-3"></i>
                                <h4>Fast Delivery</h4>
                                <p>Fast and reliable delivery service to ensure your project stays on schedule.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="bx bx-like bx-lg mb-3"></i>
                                <h4>Quality Guaranteed</h4>
                                <p>We only offer top-quality products from trusted suppliers and manufacturers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>            
        </main>
        <!-- MAIN -->

        @include('landingPage.partials.footer')
    </section>
    <!-- NAVBAR -->

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));

            // Login nav click handler
            const loginNavItem = document.querySelector('.nav-link[href="#login"]');
            if (loginNavItem) {
                loginNavItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    loginModal.show();
                });
            }

            // Register nav click handler (if you have one)
            const registerNavItem = document.querySelector('.nav-link[href="#register"]');
            if (registerNavItem) {
                registerNavItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    registerModal.show();
                });
            }

            // Switch between login and register modals
            document.getElementById('showRegister')?.addEventListener('click', function(e) {
                e.preventDefault();
                loginModal.hide();
                registerModal.show();
            });

            document.getElementById('showLogin')?.addEventListener('click', function(e) {
                e.preventDefault();
                registerModal.hide();
                loginModal.show();
            });

            // Form submissions
            document.getElementById('loginForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                // Add your login logic here
                console.log('Login submitted');
                console.log('Submitting to:', this.action);
                // loginModal.hide(); // Uncomment to close after submission
            });

            document.getElementById('registerForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                // Add your registration logic here
                console.log('Register submitted');
                // registerModal.hide(); // Uncomment to close after submission
            });

            // Forgot password handler
            document.getElementById('forgotPassword')?.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Password reset functionality would go here');
            });

            document.getElementById('registerModal')?.addEventListener('shown.bs.modal', function() {
                // On small screens, focus on first field and scroll to it
                if (window.innerHeight < 700) {
                    const firstInput = document.querySelector('#registerForm input');
                    firstInput?.focus();
                    firstInput?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>
</body>

</html>
