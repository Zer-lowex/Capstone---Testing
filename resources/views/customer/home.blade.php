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

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        @include('customer.partials.navbar')
        <!-- NAVBAR -->

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
                                <a href="{{ route('customer.products') }}" class="btn btn-primary btn-lg mt-3">View Available Products</a>
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

        @include('customer.partials.footer')
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
