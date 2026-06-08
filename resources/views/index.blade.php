@extends('layouts.app')

@section('title', 'Home - Running Gear & Apparel')

@section('content')
    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: linear-gradient(105deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1538805060514-97d9cc17730c?q=80&w=1887&auto=format');">
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <div class="hero-content">
                        <h1 class="animate__animated animate__fadeInUp">Call me kalcer runner</h1>
                        <p class="lead">It's okay, the snail pace, the important thing is my style is perfect</p>
                        <a href="{{ url('/shop') }}" class="btn btn-danger btn-lg px-5 py-3 rounded-pill">Shop Now →</a>
                    </div>
                </div>
            </div>
            <!-- Add other carousel items similarly -->
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Kategori Section -->
    <section class="container py-5">
        <h2 class="section-title">Kategori Produk</h2>
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="category-card text-center p-4">
                    <div class="category-icon-wrapper"><i class="fas fa-shoe-prints text-danger"></i></div>
                    <h4>Running Shoes</h4>
                    <a href="{{ url('/shop?cat=running') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4">Lihat Semua →</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card text-center p-4">
                    <div class="category-icon-wrapper"><i class="fas fa-mountain text-danger"></i></div>
                    <h4>Trail Run</h4>
                    <a href="{{ url('/shop?cat=trail') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4">Lihat Semua →</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card text-center p-4">
                    <div class="category-icon-wrapper"><i class="fas fa-tshirt text-danger"></i></div>
                    <h4>Apparel</h4>
                    <a href="{{ url('/shop?cat=apparel') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4">Lihat Semua →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Terlaris -->
    <section class="container py-5">
        <h2 class="section-title">Produk Terlaris</h2>
        <div class="row g-4 mt-2" id="featuredProducts">
            @foreach($featuredProducts as $product)
            <div class="col-md-3">
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id) }}'">
                    <img src="{{ $product->image_url }}" class="product-img" alt="{{ $product->name }}">
                    <div class="product-info">
                        <span class="badge bg-danger">{{ $product->category_name }}</span>
                        <h6 class="mt-2">{{ $product->name }}</h6>
                        <p class="text-danger fw-bold mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endsection