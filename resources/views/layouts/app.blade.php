<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RUNORA | @yield('title', 'Running Gear & Apparel')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- ========== NAVBAR ========== -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand logo" href="{{ url('/') }}">
                <span class="text-danger">RUN</span><span>ORA</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ url('/shop') }}">Shop</a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ url('/cart') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" id="cartCount">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="theme-toggle" id="themeToggleBtn">
                            <i class="fas fa-moon"></i>
                            <span id="themeText">Gelap</span>
                        </button>
                    </li>
                    @auth
                        <li class="nav-item" id="userInfoArea">
                            <span class="me-2 fw-semibold">{{ Auth::user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item" id="authButtons">
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm">Masuk</a>
                            <a href="{{ route('register') }}" class="btn btn-danger btn-sm ms-2">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========== MAIN CONTENT ========== -->
    <main>
        @yield('content')
    </main>

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="logo mb-3"><span class="text-danger">RUN</span><span>ORA</span></h5>
                    <p class="text-muted">Running gear & apparel for every runner. Stay stylish, keep running.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('/shop') }}">Shop</a></li>
                        <li><a href="{{ url('/cart') }}">Keranjang</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Kategori</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/shop?cat=running') }}">Running Shoes</a></li>
                        <li><a href="{{ url('/shop?cat=trail') }}">Trail Run</a></li>
                        <li><a href="{{ url('/shop?cat=apparel') }}">Apparel</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Follow Us</h6>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-3">
            <div class="text-center">
                <p class="mb-0">© 2025 RUNORA — Red. White. Black. Run with spirit.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        // Update cart count on all pages
        function updateCartCount() {
            fetch('/api/cart/count')
                .then(res => res.json())
                .then(data => {
                    const cartCount = document.getElementById('cartCount');
                    if (cartCount) cartCount.innerText = data.count;
                });
        }
        
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
    
    @stack('scripts')
</body>
</html>