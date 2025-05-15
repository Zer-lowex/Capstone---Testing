<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-2">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/customer') }}">
            <img src="{{ asset('assets/images/kc.png') }}" alt="Logo" height="50" class="me-2">
            <span class="navbar-text d-none d-lg-inline">PRIME ENTERPRISES</span>
        </a>        

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/customer') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/products') }}">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/about') }}">About Us</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                </li>

                @auth('customer')
                    <!-- Show when customer is logged in -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="customerDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                           My Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customerDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bx bx-user me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                                   <i class="bx bx-log-out me-2"></i> Logout
                                </a>
                                <form id="customer-logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Show when no customer is logged in -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Log In
                        </a>
                    </li>
                    @if(Route::has('customer.register'))
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                                Register
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>