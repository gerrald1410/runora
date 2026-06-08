<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->orWhere('id', '<=', 4)
            ->limit(4)
            ->get();
        
        return view('index', compact('featuredProducts'));
    }
}