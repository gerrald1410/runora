<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'category_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',        // validasi stok
            'discount' => 'nullable|integer|min:0|max:100', // validasi diskon
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'sizes' => 'nullable|array',
            'description' => 'required|string',
            'is_featured' => 'nullable|boolean',
        ]);

        // Upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $imageUrl = 'uploads/products/' . $imageName;
        }

        $sizes = $request->sizes ? json_encode($request->sizes) : null;

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'category_name' => $request->category_name,
            'price' => $request->price,
            'stock' => $request->stock,              // simpan stok
            'discount' => $request->discount ?? 0,   // simpan diskon
            'image_url' => $imageUrl ?? null,
            'sizes' => $sizes,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->sizes) {
            $product->sizes = json_decode($product->sizes, true);
        } else {
            $product->sizes = [];
        }
        
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'category_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',     // validasi stok
            'discount' => 'nullable|integer|min:0|max:100', // validasi diskon
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'sizes' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'category_name' => $request->category_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,              // update stok
            'discount' => $request->discount ?? 0,   // update diskon
            'sizes' => $request->sizes ? json_encode($request->sizes) : null,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ];

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                unlink(public_path($product->image_url));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image_url'] = 'uploads/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus file gambar
        if ($product->image_url && file_exists(public_path($product->image_url))) {
            unlink(public_path($product->image_url));
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }
}