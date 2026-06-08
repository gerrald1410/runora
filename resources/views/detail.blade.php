@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="container py-5">
    <a href="{{ url('/shop') }}" class="btn btn-link text-danger mb-4 px-0">
        <i class="fas fa-arrow-left"></i> Kembali ke Shop
    </a>
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image_url }}" class="img-fluid rounded-4 w-100" alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <span class="badge bg-danger mb-2">{{ $product->category_name }}</span>
            <h1 class="display-5 fw-bold">{{ $product->name }}</h1>
            <h2 class="text-danger fw-bold mt-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
            <p class="mt-4 text-secondary">{{ $product->description }}</p>
            
            <div class="mt-4">
                <label class="fw-semibold mb-2">Pilih Ukuran:</label>
                <div class="d-flex gap-2 flex-wrap" id="sizeOptions">
                    @foreach($product->sizes as $size)
                        <button class="btn-size" data-size="{{ $size }}">{{ $size }}</button>
                    @endforeach
                </div>
            </div>
            
            <button class="btn btn-danger btn-lg w-100 mt-4" id="addToCartBtn" data-product-id="{{ $product->id }}">
                <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
            </button>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let selectedSize = null;
    
    document.querySelectorAll('.btn-size').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-size').forEach(b => {
                b.classList.remove('active', 'btn-danger');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active', 'btn-danger');
            selectedSize = this.dataset.size;
        });
    });
    
    document.getElementById('addToCartBtn').addEventListener('click', async function() {
        if (!selectedSize) {
            alert('Pilih ukuran terlebih dahulu!');
            return;
        }
        
        const response = await fetch('{{ url("/cart/add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: this.dataset.productId,
                size: selectedSize,
                quantity: 1
            })
        });
        
        if (response.status === 401) {
            window.location.href = '{{ route("login") }}';
            return;
        }
        
        const data = await response.json();
        if (data.success) {
            alert('Produk ditambahkan ke keranjang');
            updateCartCount();
        }
    });
</script>
@endpush