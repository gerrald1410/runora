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
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // CRUD Utama Produk Admin (Menggunakan rute manual agar nama url sinkron dengan file Blade)
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/tambah', [AdminProductController::class, 'create'])->name('produk.create');
    Route::post('/produk/tambah', [AdminProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}', [AdminProductController::class, 'edit'])->name('produk.edit');
    
    // ─── INI JALUR UPDATE YANG TADI HILANG ───
    Route::put('/produk/update/{id}', [AdminProductController::class, 'update'])->name('produk.update');
    
    // Jalur Hapus Produk
    Route::delete('/produk/hapus/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy');
});

/*
|--------------------------------------------------------------------------
| 5. TEMPORARY ROUTES (Rute Sementara untuk Intip Tampilan Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/intip-admin', [AdminDashboard::class, 'index']);
Route::get('/intip-produk', [AdminProductController::class, 'index']);
Route::get('/intip-tambah-produk', [AdminProductController::class, 'create']);

Route::get('/intip-produk', [AdminProductController::class, 'index']);