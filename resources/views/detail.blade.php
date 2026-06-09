@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Kolom Gambar -->
        <div class="col-md-6">
            <div class="position-relative">
                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="img-fluid rounded-4 w-100">
                @if(($product->discount ?? 0) > 0)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 fs-6">
                        -{{ $product->discount }}%
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Kolom Detail Produk -->
        <div class="col-md-6">
            <!-- Nama & Kategori -->
            <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
            <p class="text-muted mb-3">{{ $product->category_name ?? $product->category }}</p>
            
            <!-- Harga dengan Diskon -->
            @php
                $hargaAsli = $product->price;
                $diskon = $product->discount ?? 0;
                $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $diskon / 100);
            @endphp
            
            @if($diskon > 0)
                <div class="mb-3">
                    <span class="text-decoration-line-through text-muted me-2 fs-5">
                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    </span>
                    <span class="text-danger fw-bold fs-2">
                        Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                    </span>
                </div>
            @else
                <h2 class="text-danger fw-bold mb-3">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</h2>
            @endif
            
            <!-- Stok -->
            <div class="mb-3">
                @if($product->stock > 0)
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-box me-1"></i> Stok: {{ $product->stock }}
                    </span>
                @else
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-exclamation-circle me-1"></i> Stok Habis
                    </span>
                @endif
            </div>
            
            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="fw-semibold mb-2">Deskripsi Produk:</label>
                <p class="text-secondary">{{ $product->description }}</p>
            </div>
            
            <!-- Pilihan Ukuran -->
            <div class="mb-4">
                <label class="fw-semibold mb-2">Pilih Ukuran <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap" id="sizeOptions">
                    @php
                        $sizes = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true);
                    @endphp
                    @if($sizes && count($sizes) > 0)
                        @foreach($sizes as $size)
                            <button type="button" class="btn-size" data-size="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    @else
                        <p class="text-muted">Ukuran tidak tersedia</p>
                    @endif
                </div>
                <input type="hidden" id="selectedSize" name="selectedSize" value="">
                <small class="text-muted" id="sizeWarning" style="display: none;">Silakan pilih ukuran terlebih dahulu</small>
            </div>
            
            <!-- Quantity & Tombol Tambah ke Keranjang -->
            <div class="mt-4">
                <label class="fw-semibold mb-2">Jumlah:</label>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <button class="btn btn-outline-secondary rounded-circle" id="decreaseQty" 
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                           style="width: 80px; text-align: center;" class="form-control text-center" 
                           {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <button class="btn btn-outline-secondary rounded-circle" id="increaseQty" 
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <button class="btn btn-danger btn-lg w-100 py-3" id="addToCartBtn" 
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $hargaSetelahDiskon }}"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-cart me-2"></i>
                    {{ $product->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-size {
        padding: 10px 20px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }
    .btn-size:hover {
        background: #f8f9fa;
        border-color: #dc3545;
    }
    .btn-size.active {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    .btn-size:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .cart-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }
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
</style>

<script>
    // DOM Elements
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const sizeButtons = document.querySelectorAll('.btn-size');
    const selectedSizeInput = document.getElementById('selectedSize');
    const sizeWarning = document.getElementById('sizeWarning');
    
    let selectedSize = null;
    
    // Update quantity buttons
    if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }
    
    if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            let maxStock = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
    
    // Size selection
    sizeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all size buttons
            sizeButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Store selected size
            selectedSize = this.getAttribute('data-size');
            selectedSizeInput.value = selectedSize;
            // Hide warning if shown
            if (sizeWarning) {
                sizeWarning.style.display = 'none';
            }
        });
    });
    
    // Add to cart functionality
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            // Validate size selection
            if (!selectedSize) {
                if (sizeWarning) {
                    sizeWarning.style.display = 'block';
                    setTimeout(() => {
                        sizeWarning.style.display = 'none';
                    }, 3000);
                }
                return;
            }
            
            const productId = this.getAttribute('data-product-id');
            const quantity = parseInt(quantityInput.value);
            
            // Disable button while processing
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        size: selectedSize
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Show success message
                    showToast('success', data.message || 'Produk berhasil ditambahkan ke keranjang!');
                    
                    // Update cart count in navbar (if exists)
                    updateCartCount(data.cart_count);
                } else {
                    showToast('error', data.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang';
            }
        });
    }
    
    // Show toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = 'cart-toast';
        toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
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
    
    // Update cart count in navbar
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count;
                cartBadge.style.display = 'inline-block';
            } else {
                cartBadge.style.display = 'none';
            }
        }
        
        // Alternatively, trigger a custom event
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count } }));
    }
    
    // Manual input validation for quantity
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            const min = parseInt(this.getAttribute('min')) || 1;
            const max = parseInt(this.getAttribute('max')) || 999;
            
            if (isNaN(value) || value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
                showToast('warning', `Stok hanya tersedia ${max} item`);
            }
        });
    }
</script>
@endsection