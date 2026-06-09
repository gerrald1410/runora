<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. GUEST / AUTH ROUTES (Hanya Bisa Diakses Jika Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout & API Cek User (Bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/api/user', [AuthController::class, 'checkUser']);
});

/*
|--------------------------------------------------------------------------
| 2. PUBLIC ROUTES (Bisa Diakses Semua Orang Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/detail/{id}', [ShopController::class, 'detail'])->name('product.detail');

/*
|--------------------------------------------------------------------------
| 3. PEMBELI ROUTES (Wajib Login & Memiliki Role Pembeli)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::get('/api/cart/count', [CartController::class, 'count']);
});

/*
|--------------------------------------------------------------------------
| 4. OFFICIAL ADMIN ROUTES (Wajib Login & Memiliki Role Admin)
|--------------------------------------------------------------------------
| Semua halaman di dalam grup ini otomatis aman. Sekali login sebagai admin, 
| kamu bisa bebas berpindah halaman tanpa diminta login lagi.
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama Admin (Akses: /admin/dashboard)
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Tabel Daftar Produk (Akses: /admin/produk)
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
    
    // Form Tambah Produk Baru (Akses: /admin/produk/tambah)
    Route::get('/produk/tambah', [AdminProductController::class, 'create'])->name('produk.create');
    Route::post('/produk/tambah', [AdminProductController::class, 'store'])->name('produk.store');
    
    // Form Edit Produk (Akses: /admin/produk/edit/{id})
    Route::get('/produk/edit/{id}', [AdminProductController::class, 'edit'])->name('produk.edit');
    
    // Proses Update Data (Mesin Latar Belakang Form Edit)
    Route::put('/produk/update/{id}', [AdminProductController::class, 'update'])->name('produk.update');
    
    // Proses Hapus Produk (Aksi Tombol Trash)
    Route::delete('/produk/hapus/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy');
});