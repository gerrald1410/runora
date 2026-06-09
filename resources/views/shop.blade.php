@extends('layouts.app')

@section('title', 'Shop - Running Gear & Apparel')

@section('content')
<section class="container py-4">
    <h2 class="section-title">Semua Produk</h2>
    
    <div class="d-flex gap-2 mb-4 flex-wrap" id="categoryFilters">
        <button class="btn btn-filter {{ $category == 'all' ? 'active' : '' }}" data-cat="all">Semua</button>
        <button class="btn btn-filter {{ $category == 'running' ? 'active' : '' }}" data-cat="running">Running</button>
        <button class="btn btn-filter {{ $category == 'trail' ? 'active' : '' }}" data-cat="trail">Trail Run</button>
        <button class="btn btn-filter {{ $category == 'apparel' ? 'active' : '' }}" data-cat="apparel">Apparel</button>
    </div>
    
    <div class="row g-4" id="productsGrid">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id) }}'">
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">                <div class="product-info">
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

@push('scripts')
<script>
    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.dataset.cat;
            window.location.href = `/shop?cat=${cat}`;
        });
    });
</script>
@endpush