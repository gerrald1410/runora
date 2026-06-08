<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;

// 1. GUEST / AUTH ROUTES
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

// 2. PUBLIC ROUTES (Bisa Diakses Semua Orang)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/detail/{id}', [ShopController::class, 'detail'])->name('product.detail');

// 3. PEMBELI ROUTES (Wajib Login & Role Pembeli)
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::get('/api/cart/count', [CartController::class, 'count']);
});

// 4. ADMIN ROUTES (Wajib Login & Role Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Dashboard Admin (Membuka file blade yang kita buat kemarin)
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Kelola CRUD Produk Admin
    Route::resource('products', AdminProductController::class);
    
});

//5. 
Route::middleware(['auth'])->group(function () {
    Route::get('/produk/tambah', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk/tambah', [ProductController::class, 'store'])->name('produk.store');
});