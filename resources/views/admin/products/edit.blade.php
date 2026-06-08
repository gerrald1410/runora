@extends('layouts.app')

@section('title', 'Edit Produk - Runora')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Produk
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-edit text-primary me-2"></i>EDIT DATA PRODUK</h5>
            <p class="text-muted small mb-0">Ubah informasi detail produk Runora di bawah ini.</p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" 
                               class="form-control @error('nama_produk') is-invalid @enderror" 
                               value="{{ old('nama_produk', $product->nama_produk) }}" required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Frozen Food" {{ old('kategori', $product->kategori) == 'Frozen Food' ? 'selected' : '' }}>Frozen Food</option>
                            <option value="Olahan Daging" {{ old('kategori', $product->kategori) == 'Olahan Daging' ? 'selected' : '' }}>Olahan Daging</option>
                            <option value="Dairy Product" {{ old('kategori', $product->kategori) == 'Dairy Product' ? 'selected' : '' }}>Dairy Product</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="number" name="harga" min="0" 
                                   class="form-control @error('harga') is-invalid @enderror" 
                                   value="{{ old('harga', $product->harga) }}" required>
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary">Diskon (%)</label>
                        <div class="input-group">
                            <input type="number" name="diskon" min="0" max="100" 
                                   class="form-control @error('diskon') is-invalid @enderror" 
                                   value="{{ old('diskon', $product->diskon) }}">
                            <span class="input-group-text bg-light fw-bold text-muted">%</span>
                        </div>
                        @error('diskon')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary">Stok Gudang <span class="text-danger">*</span></label>
                        <input type="number" name="stok" min="0" 
                               class="form-control @error('stok') is-invalid @enderror" 
                               value="{{ old('stok', $product->stok) }}" required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold text-secondary">Nama File Gambar <span class="text-danger">*</span></label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light"><i class="fas fa-image text-muted"></i></span>
                            <input type="text" name="gambar" 
                                   class="form-control @error('gambar') is-invalid @enderror" 
                                   value="{{ old('gambar', $product->gambar) }}" placeholder="contoh: sepatu-runora.jpg" required>
                        </div>
                        <div class="form-text text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Pastikan file gambar sudah di-upload ke folder <code>public/assets/images/</code>.
                        </div>
                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 fw-bold rounded-3">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection