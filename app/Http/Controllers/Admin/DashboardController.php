<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $totalProduk = Product::count();

        $totalDiskon = Product::where('diskon', '>', 0)->count();

        return view('admin.dashboard', [
            'totalProduk' => $totalProduk,
            'totalDiskon' => $totalDiskon
        ]);
    }
}