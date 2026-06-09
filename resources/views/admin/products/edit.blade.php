@extends('layouts.app')

@section('title', 'Admin - Edit Produk')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-link text-danger">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
    
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-edit text-danger me-2"></i>Edit Produk
            </h3>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="running" {{ old('category', $product->category) == 'running' ? 'selected' : '' }}>Running Shoes</option>
                            <option value="trail" {{ old('category', $product->category) == 'trail' ? 'selected' : '' }}>Trail Run</option>
                            <option value="apparel" {{ old('category', $product->category) == 'apparel' ? 'selected' : '' }}>Apparel</option>
                            <option value="accessories" {{ old('category', $product->category) == 'accessories' ? 'selected' : '' }}>Aksesoris</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Kategori Tampilan <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" 
                               value="{{ old('category_name', $product->category_name) }}" required>
                        @error('category_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $product->price) }}" required min="0">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Stok Produk <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $product->stock ?? 0) }}" required min="0">
                        <small class="text-muted">Jumlah barang tersedia</small>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Diskon (%)</label>
                        <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" 
                               value="{{ old('discount', $product->discount ?? 0) }}" min="0" max="100">
                        <small class="text-muted">Persentase diskon (0-100%)</small>
                        @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        @if($product->image_url && file_exists(public_path($product->image_url)))
                            <div class="mb-2">
                                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="max-width: 150px; height: auto; border-radius: 8px;">
                                <small class="text-muted d-block">Gambar saat ini</small>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Ukuran</label>
                        <div class="row">
                            @php
                                $sizeOptions = ['38', '39', '40', '41', '42', '43', 'S', 'M', 'L', 'XL'];
                                // Handle jika $product->sizes sudah array atau masih string JSON
                                $currentSizes = [];
                                if (!empty($product->sizes)) {
                                    if (is_array($product->sizes)) {
                                        $currentSizes = $product->sizes;
                                    } else {
                                        $currentSizes = json_decode($product->sizes, true) ?? [];
                                    }
                                }
                                $oldSizes = old('sizes', $currentSizes);
                            @endphp
                            @foreach($sizeOptions as $size)
                            <div class="col-auto mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" 
                                           id="size_{{ $size }}" {{ in_array($size, (array)$oldSizes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size_{{ $size }}">
                                        {{ $size }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Centang ukuran yang tersedia</small>
                        @error('sizes')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" class="form-check-input" value="1" 
                                   id="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                Jadikan Produk Unggulan (Featured)
                            </label>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-save me-2"></i>Update Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection