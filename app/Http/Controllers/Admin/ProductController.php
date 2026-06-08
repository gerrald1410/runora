<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $kategori = $request->input('kategori', '');
        $harga = $request->input('harga', '');

        // Mulai query dasar dengan pengurutan terbaru
        $query = Product::orderBy('created_at', 'desc');

        // Logika Pencarian Nama
        if (!empty($search)) {
            $query->where('nama_produk', 'LIKE', '%' . $search . '%');
        }

        // Logika Filter Kategori
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        // Logika Filter Rentang Harga
        if ($harga === 'murah') {
            $query->where('harga', '<', 50000);
        } elseif ($harga === 'mahal') {
            $query->where('harga', '>=', 50000);
        }

        // Ambil data menggunakan PAGINATE agar halaman tidak kepanjangan (10 data per halaman)
        $products = $query->paginate(10)->withQueryString(); 

        return view('admin.products.index', compact('products', 'search', 'kategori', 'harga'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'stok' => 'required|integer|min:0',
            'gambar' => 'required|string', // atau 'image' jika handle upload file
        ]);

        Product::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'diskon' => $request->diskon ?? 0,
            'stok' => $request->stok,
            'gambar' => $request->gambar,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'stok' => 'required|integer|min:0',
            'gambar' => 'required|string',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'diskon' => $request->diskon ?? 0,
            'stok' => $request->stok,
            'gambar' => $request->gambar,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}