<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        
        $totalDiskon = 0;
        try {
            if (\Schema::hasColumn('products', 'diskon')) {
                $totalDiskon = Product::where('diskon', '>', 0)->count();
            }
        } catch (\Exception $e) {
            $totalDiskon = 0;
        }
        
        return view('admin.dashboard', [
            'totalProduk' => $totalProduk,
            'totalDiskon' => $totalDiskon
        ]);
    }
}