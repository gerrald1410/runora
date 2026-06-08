@extends('layouts.admin')

@section('title', 'Data Produk - Runora')

@section('content')

{{-- ── 1. POPUP NOTIFIKASI SUKSES ── --}}
@if(session('success'))
<div id="successPopup" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-80 text-center shadow-xl animate-bounce">
        <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
        <h2 class="text-lg font-bold">Berhasil!</h2>
        <p class="text-gray-500 text-sm mb-4">
            {{ session('success') }}
        </p>
        <button onclick="closePopup()" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-600">
            OK
        </button>
    </div>
</div>
@endif

{{-- ── 2. HEADER HALAMAN ── --}}
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-2xl font-bold uppercase">DATA PRODUK</h1>
        <p class="text-gray-600">Kelola daftar stok dan harga produk Anda</p>
    </div>

    <a href="{{ route('admin.produk.create') }}" class="bg-red-600 text-white px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition hover:bg-red-700">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

{{-- ── 3. FORM FILTER PENCARIAN ── --}}
<form method="GET" action="{{ route('admin.produk.index') }}" class="bg-white p-4 rounded-xl shadow mb-6 flex flex-wrap gap-4">
    <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk..." class="px-4 py-2 border rounded-lg w-64 outline-none focus:border-red-500">

    <select name="kategori" class="px-4 py-2 border rounded-lg outline-none">
        <option value="">Semua Kategori</option>
        <option value="Trail Run" {{ $kategori == 'Trail Run' ? 'selected' : '' }}>Trail Run</option>
        <option value="Running Shoes" {{ $kategori == 'Running Shoes' ? 'selected' : '' }}>Running Shoes</option>
        <option value="Apparel" {{ $kategori == 'Apparel' ? 'selected' : '' }}>Apparel</option>
        <option value="Aksesoris" {{ $kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
    </select>

    <select name="urutkan" class="px-4 py-2 border rounded-lg outline-none">
        <option value="">Semua Harga</option>
        <option value="termurah" {{ $urutkan == 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
        <option value="termahal" {{ $urutkan == 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
    </select>

    <button type="submit" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition">Cari</button>
    <a href="{{ route('admin.produk.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">Reset</a>
</form>

{{-- ── 4. TABEL UTAMA DATA PRODUK ── --}}
<div class="bg-white rounded-xl shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4" width="70">No</th>
                    <th class="p-4">Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4" width="100">Diskon</th>
                    <th class="p-4 text-center" width="100">Stok</th>
                    <th class="p-4 text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $row)
                @php
                    $hargaAsli = $row->harga ?? 0; 
                    $diskonPercent = $row->diskon ?? 0; // Mengunci data dari kolom 'diskon' asli
                    $hargaDiskon = $hargaAsli - ($hargaAsli * $diskonPercent / 100);
                @endphp
                <tr class="border-t hover:bg-gray-50/80 transition">
                    <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                    <td class="p-4 flex items-center gap-3">
                        @if($row->gambar)
                            <img src="{{ asset('storage/products/' . $row->gambar) }}" onclick="openImageModal(this.src)" class="w-10 h-10 rounded-lg object-cover cursor-pointer hover:scale-110 transition shadow-sm">
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><i class="fas fa-image"></i></div>
                        @endif
                        {{-- ── FIX 1: Ganti nama_produk menjadi nama ── --}}
                        <span class="font-semibold text-gray-800">{{ $row->nama }}</span>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-medium">
                            {{-- ── FIX 2: Menampilkan kategori asli dari database ── --}}
                            {{ $row->kategori ?? 'Umum' }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($diskonPercent > 0)
                            <span class="line-through text-gray-400 text-xs">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span><br>
                            <span class="text-red-600 font-bold">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        @else
                            <span class="font-bold text-gray-800">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($diskonPercent > 0)
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded text-xs font-bold">{{ $diskonPercent }}%</span>
                        @else
                            <span class="text-gray-300 text-xs">-</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $row->stok < 10 ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700' }}">
                            {{ $row->stok }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.produk.edit', $row->id) }}" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition shadow-sm"><i class="fas fa-edit"></i></a>
                            
                            <form action="{{ route('admin.produk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">Belum ada data produk atau tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── 5. MODAL ZOOM DETAIL GAMBAR ── --}}
<div id="imageModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <span onclick="closeImageModal()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer hover:text-gray-300">&times;</span>
    <img id="modalImg" class="max-w-[80%] max-h-[80%] rounded-xl shadow-2xl transition-all">
</div>

@endsection

@push('scripts')
<script>
function openImageModal(src){
    document.getElementById("imageModal").classList.remove("hidden");
    document.getElementById("imageModal").classList.add("flex");
    document.getElementById("modalImg").src = src;
}

function closeImageModal(){
    document.getElementById("imageModal").classList.add("hidden");
    document.getElementById("imageModal").classList.remove("flex");
}

function closePopup(){
    document.getElementById('successPopup').style.display = 'none';
}
</script>
@endpush