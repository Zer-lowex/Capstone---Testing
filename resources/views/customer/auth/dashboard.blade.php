@extends('customer.auth.layout2')
@section('title', 'Home')

@section('content')
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
                            <h1 class="display-5 fw-bold">Welcome to KC Prime Enterprises, {{ auth()->guard('customer')->user()->first_name }} {{ auth()->guard('customer')->user()->last_name }}!</h1>
                            <p class="lead">Your trusted partner in quality construction and hardware supplies.</p>
                            <a href="{{ route('customer.auth.products') }}" class="btn btn-primary btn-lg mt-3">View Available Products</a>
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
@endsection
