<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('cat', 'all');
        
        if ($category === 'all') {
            $products = Product::all();
        } else {
            $products = Product::where('category', $category)->get();
        }
        
        if ($request->ajax()) {
            return response()->json($products);
        }
        
        return view('shop', compact('products', 'category'));
    }
    
    public function detail($id)
    {
        $product = Product::findOrFail($id);
        
        // Decode sizes dari JSON ke array
        if ($product->sizes) {
            $product->sizes = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true);
        } else {
            $product->sizes = [];
        }
        
        return view('detail', compact('product'));
    }
}