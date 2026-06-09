<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'image_url' => 'nullable|url',
            'sizes' => 'nullable|string',
        ]);

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'category_name' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'sizes' => $request->sizes,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'category_name' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'sizes' => $request->sizes,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }
}