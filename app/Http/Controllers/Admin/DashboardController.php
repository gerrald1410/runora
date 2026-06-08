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
        $totalProduk = Product::count();
        $totalPesanan = Order::count();

        #Dummy
        $topProduk = collect([
            (object)['nama_produk' => 'RUNORA Trail & Outdoor (Army Green)', 'gambar' => null, 'total_terjual' => 300, 'total_pendapatan' => 500000000],
            (object)['nama_produk' => 'RUNORA Ultra Comfort BOA (Pink & White)', 'gambar' => null, 'total_terjual' => 280, 'total_pendapatan' => 40000000],
            (object)['nama_produk' => 'RUNORA Peacock Premium (White & Grey)', 'gambar' => null, 'total_terjual' => 220, 'total_pendapatan' => 2000000],
            (object)['nama_produk' => 'RUNORA Heritage Batik Performance Tee (Maroon)', 'gambar' => null, 'total_terjual' => 100, 'total_pendapatan' => 1000000],
        ]);

        $stokMenipis  = 11;
        $stokTersedia = 30;
        $stokHabis    = 9;

        $aktivitasTerbaru = collect([
            (object)['tipe' => 'pesanan_baru', 'deskripsi' => 'Pesanan baru #NVI-2026-1201-0002', 'user_name' => 'Prawoko', 'waktu' => '5 menit lalu'],
            (object)['tipe' => 'produk_ditambah', 'deskripsi' => 'Produk baru ditambahkan', 'user_name' => 'Rara Riri Roro', 'waktu' => '20 menit lalu'],
            (object)['tipe' => 'pesanan_baru', 'deskripsi' => 'Pesanan baru #NVI-2026-1201-0002', 'user_name' => 'Satu Dua Tiga', 'waktu' => '50 menit lalu'],
        ]);

        $grafikPenjualan = collect([
            ['bulan' => 'Jan', 'total' => 30000000], ['bulan' => 'Feb', 'total' => 45000000],
            ['bulan' => 'Mar', 'total' => 55000000], ['bulan' => 'Apr', 'total' => 70000000],
            ['bulan' => 'Mei', 'total' => 65000000], ['bulan' => 'Jun', 'total' => 85000000],
            ['bulan' => 'Jul', 'total' => 90000000], ['bulan' => 'Agu', 'total' => 75000000],
            ['bulan' => 'Sep', 'total' => 95000000], ['bulan' => 'Okt', 'total' => 110000000],
            ['bulan' => 'Nov', 'total' => 100000000], ['bulan' => 'Des', 'total' => 120000000],
        ]);

        $statusPesanan = [
            'menunggu_pembayaran' => 3,
            'diproses'            => 5,
            'dikirim'             => 12,
            'selesai'             => 20
        ];

        return view('admin.dashboard', compact(
            'totalPenjualan','totalPesanan', 'totalProduk',
            'topProduk', 'stokMenipis', 'stokTersedia', 'stokHabis',
            'aktivitasTerbaru', 'grafikPenjualan', 'statusPesanan'
        ));
    }
}