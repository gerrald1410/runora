<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $totalItems = $cartItems->sum('quantity');
        
        return view('cart', compact('cartItems', 'total', 'totalItems'));
    }
    
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        // Validasi stok
        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok produk ini habis!'
            ], 400);
        }
        
        $quantity = $request->quantity ?? 1;
        
        // Validasi quantity tidak melebihi stok
        if ($quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia (Stok: ' . $product->stock . ')'
            ], 400);
        }
        
        $cart = session()->get('cart', []);
        
        $size = $request->size;
        $key = $product->id . '_' . $size;
        
        if (isset($cart[$key])) {
            // Cek stok untuk penambahan quantity
            $newQuantity = $cart[$key]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah total melebihi stok yang tersedia (Stok: ' . $product->stock . ')'
                ], 400);
            }
            $cart[$key]['quantity'] = $newQuantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount ?? 0,
                'size' => $size,
                'quantity' => $quantity,
                'image' => $product->image_url,
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $cart->quantity = $request->quantity;
        $cart->save();
        
        return response()->json(['success' => true]);
    }
    
    public function remove($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $cart->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
    
    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}