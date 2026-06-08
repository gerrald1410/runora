<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan Form Tambah Produk
    public function create()
    {
        return view('admin.produk-tambah');
    }

    // Proses Simpan Data ke Database
    public function store(Request $request)
    {
        // 1. Validasi Input (Otomatis mencegah error SQL & input kosong)
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'harga'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        // 2. Handle Upload Gambar ke folder storage
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // Menyimpan ke: storage/app/public/products
            $path = $file->store('products', 'public'); 
            $namaGambar = basename($path);
        }

        // 3. Simpan ke database menggunakan Eloquent Model
        Product::create([
            'nama_produk' => $validated['nama'],
            'category'    => $validated['kategori'], // Sesuaikan nama kolom di DB-mu
            'harga'       => $validated['harga'],
            'stok'        => $validated['stok'],
            'diskon'      => $validated['diskon'] ?? 0,
            'description' => $validated['deskripsi'],
            'gambar'      => $namaGambar,
        ]);

        // 4. Redirect ke halaman daftar produk dengan alert sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }
}