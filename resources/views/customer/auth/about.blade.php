@extends('customer.auth.layout2')

@section('title', 'About Us')

@section('content')
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

    <!-- Mission Section -->
    <section class="mission py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Our Mission</h2>
            <p class="lead mx-auto" style="max-width: 800px;">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut laudantium libero, velit atque facere a reprehenderit commodi nihil dolor saepe consequuntur, suscipit doloribus voluptates magni deleniti autem sunt perspiciatis expedita?
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box text-center p-4 h-100">
                        <i class='bx bx-box bx-lg mb-3'></i>
                        <h4>Wide Selection</h4>
                        <p>We offer a wide range of construction and hardware supplies to meet all your needs.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box text-center p-4 h-100">
                        <i class='bx bxs-truck bx-lg mb-3'></i>
                        <h4>Fast Delivery</h4>
                        <p>Reliable same-day or next-day delivery within Nairobi and surrounding areas.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box text-center p-4 h-100">
                        <i class='bx bx-like bx-lg mb-3'></i>
                        <h4>Quality Guaranteed</h4>
                        <p>All products meet Kenyan standards and come with quality assurance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta py-5 text-white text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Ready to Start Your Project?</h2>
            <p class="lead mb-4">We're here to help with all your construction and hardware needs.</p>
        </div>
    </section>

    <section class="kc-hero-section d-flex align-items-center">
        <div class="container-fluid">
            <div class="row">
                <!-- Text Side -->
                <div class="col-md-5 d-flex align-items-center justify-content-center text-center kc-text-box px-4">
                    <div>
                        <h1 class="display-5 fw-bold">KC Prime Enterprises</h1>
                        <p class="lead">29 Dumaguete - Palinpinon Road</p>
                        <a href="{{ route('customer.products') }}" class="btn btn-primary btn-lg mt-3">View Available Products</a>
                    </div>
                </div>
                <!-- Map Side -->
                <div class="col-md-6">
                    <div class="kc-map-container">
                        <div class="kc-map-embed">
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1746715711553!6m8!1m7!1sTviHgKyDVB0BZ62cU8yi5A!2m2!1d9.306789153095796!2d123.3034066106958!3f209.99!4f-6.400000000000006!5f0.5970117501821992"
                                    width="600" 
                                    height="450" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    aria-label="KC Prime Enterprises Location Map">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection