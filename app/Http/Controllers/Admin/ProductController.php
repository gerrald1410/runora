<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $kategori = $request->input('kategori', '');
        $urutkan  = $request->input('urutkan', ''); 

        $query = Product::query();

        // Filter: Pencarian Nama (Disesuaikan jadi 'nama')
        if (!empty($search)) {
            $query->where('nama', 'LIKE', '%' . $search . '%');
        }

        // Filter: Kategori (Disesuaikan jadi 'kategori')
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        // Logika Pengurutan Harga (Sorting)
        if ($urutkan === 'termurah') {
            $query->orderBy('harga', 'asc');  
        } elseif ($urutkan === 'termahal') {
            $query->orderBy('harga', 'desc'); 
        } else {
            $query->orderBy('id', 'desc'); 
        }

        $products = $query->get();

        return view('admin.produk-list', compact('products', 'search', 'kategori', 'urutkan'));
    }

    public function create()
    {
        return view('admin.produk-tambah');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'harga'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('products', 'public'); 
            $namaGambar = basename($path);
        }

        Product::create([
            'nama'      => $validated['nama'],
            'kategori'  => $validated['kategori'], 
            'harga'     => $validated['harga'],
            'stok'      => $validated['stok'],
            'diskon'    => $validated['diskon'] ?? 0,
            'deskripsi' => $validated['deskripsi'],
            'gambar'    => $namaGambar,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        return view('admin.produk-edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'harga'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $namaGambar = $product->gambar;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada gambar baru yang di-upload
            if ($product->gambar) {
                Storage::disk('public')->delete('products/' . $product->gambar);
            }
            $file = $request->file('gambar');
            $path = $file->store('products', 'public'); 
            $namaGambar = basename($path);
        }

        // ── FIX: Nama kolom disamakan menjadi Bahasa Indonesia ──
        $product->update([
            'nama'      => $validated['nama'],
            'kategori'  => $validated['kategori'], 
            'harga'     => $validated['harga'],
            'stok'      => $validated['stok'],
            'diskon'    => $validated['diskon'] ?? 0,
            'deskripsi' => $validated['deskripsi'],
            'gambar'    => $namaGambar,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar) {
            Storage::disk('public')->delete('products/' . $product->gambar);
        }

        $product->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}