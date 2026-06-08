@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="container py-5">
    <h2 class="section-title">Keranjang Belanja</h2>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body" id="cartItems">
                    @forelse($cartItems as $item)
                    <div class="cart-item d-flex justify-content-between align-items-center border-bottom pb-3 mb-3" data-cart-id="{{ $item->id }}">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                            <small class="text-muted">Ukuran: {{ $item->size }}</small><br>
                            <span class="text-danger fw-bold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-danger update-qty" data-change="-1">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="mx-1 fw-semibold qty-text" style="min-width: 30px; text-align: center;">{{ $item->quantity }}</span>
                            <button class="btn btn-sm btn-outline-danger update-qty" data-change="1">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger ms-2 remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Keranjang belanja kosong</p>
                        <a href="{{ url('/shop') }}" class="btn btn-danger">Mulai Belanja</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold">Ringkasan Belanja</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Item:</span>
                        <span class="fw-semibold" id="totalItems">{{ $totalItems }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga:</span>
                        <span class="fw-bold fs-5 text-danger" id="totalPrice">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <button class="btn btn-danger w-100 py-2" id="checkoutBtn">
                        <i class="fas fa-credit-card me-2"></i>Checkout
                    </button>
                    <a href="{{ url('/shop') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-shopping-bag me-2"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    async function updateCart() {
        const response = await fetch('{{ url("/api/cart/count") }}');
        const data = await response.json();
        updateCartCount();
    }
    
    document.querySelectorAll('.update-qty').forEach(btn => {
        btn.addEventListener('click', async function() {
            const cartItem = this.closest('.cart-item');
            const cartId = cartItem.dataset.cartId;
            const change = parseInt(this.dataset.change);
            const qtySpan = cartItem.querySelector('.qty-text');
            let newQty = parseInt(qtySpan.innerText) + change;
            
            if (newQty <= 0) {
                if (confirm('Hapus produk ini dari keranjang?')) {
                    await fetch(`{{ url('/cart/remove') }}/${cartId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    cartItem.remove();
                }
            } else {
                await fetch(`{{ url('/cart/update') }}/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                qtySpan.innerText = newQty;
            }
            
            window.location.reload();
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (confirm('Hapus produk ini dari keranjang?')) {
                const cartItem = this.closest('.cart-item');
                const cartId = cartItem.dataset.cartId;
                
                await fetch(`{{ url('/cart/remove') }}/${cartId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                window.location.reload();
            }
        });
    });
    
    document.getElementById('checkoutBtn')?.addEventListener('click', function() {
        alert('Terima kasih! Pesanan Anda akan diproses (demo).');
        // Clear cart logic here
    });
</script>
@endpush