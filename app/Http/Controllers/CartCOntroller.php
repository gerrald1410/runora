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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'integer|min:1'
        ]);
        
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();
        
        if ($cart) {
            $cart->quantity += $request->quantity ?? 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'size' => $request->size,
                'quantity' => $request->quantity ?? 1
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
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