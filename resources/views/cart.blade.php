@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="container py-5">
    <h2 class="section-title">Keranjang Belanja</h2>
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body" id="cartItems">
                    @forelse($cartItems as $item)
                    @php
                        // Hitung harga setelah diskon
                        $diskon = $item->product->discount ?? 0;
                        $hargaAsli = $item->product->price;
                        $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $diskon / 100);
                        $subtotal = $hargaSetelahDiskon * $item->quantity;
                    @endphp
                    <div class="cart-item d-flex justify-content-between align-items-center border-bottom pb-3 mb-3" 
                         data-cart-id="{{ $item->id }}"
                         data-product-id="{{ $item->product->id }}"
                         data-product-stock="{{ $item->product->stock }}">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Ukuran: {{ $item->size }}</small><br>
                                    
                                    {{-- Tampilkan harga dengan diskon --}}
                                    @if($diskon > 0)
                                        <small class="text-decoration-line-through text-muted">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </small>
                                        <span class="text-danger fw-bold ms-1">
                                            Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                                        </span>
                                        <small class="text-success">(-{{ $diskon }}%)</small>
                                    @else
                                        <span class="text-danger fw-bold">
                                            Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    
                                    {{-- Tampilkan peringatan stok --}}
                                    @if($item->quantity > $item->product->stock)
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Stok tersisa {{ $item->product->stock }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-danger update-qty" data-change="-1" 
                                    {{ $item->product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="mx-1 fw-semibold qty-text" style="min-width: 30px; text-align: center;">
                                {{ $item->quantity }}
                            </span>
                            <button class="btn btn-sm btn-outline-danger update-qty" data-change="1"
                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
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
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Diskon:</span>
                        <span class="text-success" id="totalDiscount">Rp {{ number_format($totalDiscount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga:</span>
                        <span class="fw-bold fs-5 text-danger" id="totalPrice">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- Cek apakah ada item yang melebihi stok --}}
                    @php
                        $hasStockIssue = false;
                        foreach($cartItems as $item) {
                            if($item->quantity > $item->product->stock || $item->product->stock <= 0) {
                                $hasStockIssue = true;
                                break;
                            }
                        }
                    @endphp
                    
                    @if($hasStockIssue)
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ada produk yang stoknya tidak mencukupi. Silakan periksa kembali keranjang Anda.
                        </div>
                    @endif
                    
                    <button class="btn btn-danger w-100 py-2" id="checkoutBtn" 
                            {{ $totalItems == 0 || $hasStockIssue ? 'disabled' : '' }}>
                        <i class="fas fa-credit-card me-2"></i>
                        {{ $totalItems == 0 ? 'Keranjang Kosong' : ($hasStockIssue ? 'Stok Tidak Cukup' : 'Checkout') }}
                    </button>
                    <a href="{{ url('/shop') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-shopping-bag me-2"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .cart-item {
        transition: all 0.3s ease;
    }
    .cart-item:hover {
        background-color: #f8f9fa;
        padding-left: 10px;
        border-radius: 8px;
    }
    .btn-outline-danger:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection

@push('scripts')
<script>
    // Update quantity
    document.querySelectorAll('.update-qty').forEach(btn => {
        btn.addEventListener('click', async function() {
            const cartItem = this.closest('.cart-item');
            const cartId = cartItem.dataset.cartId;
            const productStock = parseInt(cartItem.dataset.productStock);
            const change = parseInt(this.dataset.change);
            const qtySpan = cartItem.querySelector('.qty-text');
            let newQty = parseInt(qtySpan.innerText) + change;
            
            // Validate quantity
            if (newQty > productStock) {
                showToast('error', `Stok hanya tersisa ${productStock} item`);
                return;
            }
            
            if (newQty <= 0) {
                if (confirm('Hapus produk ini dari keranjang?')) {
                    await removeCartItem(cartId);
                    cartItem.remove();
                    updateCartSummary();
                }
            } else {
                const response = await fetch(`{{ url('/cart/update') }}/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                
                if (response.ok) {
                    qtySpan.innerText = newQty;
                    updateCartSummary();
                } else {
                    const data = await response.json();
                    showToast('error', data.message || 'Gagal mengupdate jumlah');
                }
            }
        });
    });
    
    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (confirm('Hapus produk ini dari keranjang?')) {
                const cartItem = this.closest('.cart-item');
                const cartId = cartItem.dataset.cartId;
                
                await removeCartItem(cartId);
                cartItem.remove();
                updateCartSummary();
            }
        });
    });
    
    // Remove cart item function
    async function removeCartItem(cartId) {
        await fetch(`{{ url('/cart/remove') }}/${cartId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    }
    
    // Checkout
    document.getElementById('checkoutBtn')?.addEventListener('click', async function() {
        if (!confirm('Yakin ingin melanjutkan checkout?')) {
            return;
        }
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        
        try {
            const response = await fetch('{{ url("/cart/checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showToast('success', data.message || 'Checkout berhasil! Terima kasih sudah berbelanja.');
                setTimeout(() => {
                    window.location.href = '{{ url("/shop") }}';
                }, 2000);
            } else {
                showToast('error', data.message || 'Checkout gagal. Silakan coba lagi.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
    
    // Update cart summary (total items, total price, total discount)
    async function updateCartSummary() {
        const response = await fetch('{{ url("/api/cart/summary") }}');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalItems').innerText = data.totalItems;
            document.getElementById('totalPrice').innerHTML = 'Rp ' + formatNumber(data.totalPrice);
            
            if (document.getElementById('totalDiscount')) {
                document.getElementById('totalDiscount').innerHTML = 'Rp ' + formatNumber(data.totalDiscount);
            }
            
            // Update cart count in navbar
            updateNavbarCartCount(data.totalItems);
            
            // Check if cart is empty
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                window.location.reload();
            }
            
            // Check stock issues
            const hasStockIssue = data.hasStockIssue || false;
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                if (data.totalItems === 0) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Keranjang Kosong';
                } else if (hasStockIssue) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Stok Tidak Cukup';
                } else {
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Checkout';
                }
            }
        }
    }
    
    // Update navbar cart count
    function updateNavbarCartCount(count) {
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count;
                cartBadge.style.display = 'inline-block';
            } else {
                cartBadge.style.display = 'none';
            }
        }
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count } }));
    }
    
    // Format number to Indonesian currency format
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Show toast notification
    function showToast(type, message) {
        // Remove existing toast
        const existingToast = document.querySelector('.cart-toast');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = 'cart-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2 fs-5"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Add animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush