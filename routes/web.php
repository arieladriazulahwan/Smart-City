<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UmkmController;

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendReset']);
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset']);
Route::post('/reset-password', [PasswordResetController::class, 'processReset']);

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'redirectByRole']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword']);
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword']);

    Route::get('/produk', [ProductController::class, 'index']);

    Route::middleware('role:pembeli')->group(function () {
        Route::post('/keranjang/{id}/add', [CartController::class, 'add']);
        Route::get('/keranjang', [CartController::class, 'index']);
        Route::post('/keranjang/{id}/update', [CartController::class, 'update']);
        Route::post('/keranjang/{id}/remove', [CartController::class, 'remove']);
        Route::post('/checkout', [CartController::class, 'checkout']);
        Route::get('/pesanan', [OrderController::class, 'buyerOrders']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
        Route::get('/admin/kategori', [CategoryController::class, 'index']);
        Route::get('/admin/kategori/create', [CategoryController::class, 'create']);
        Route::post('/admin/kategori/store', [CategoryController::class, 'store']);
        Route::get('/admin/kategori/{id}/edit', [CategoryController::class, 'edit']);
        Route::post('/admin/kategori/{id}/update', [CategoryController::class, 'update']);
        Route::post('/admin/kategori/{id}/delete', [CategoryController::class, 'destroy']);
        Route::post('/admin/transaksi/{id}/status', [OrderController::class, 'updateStatus']);
        Route::post('/umkm/{id}/verify', [UmkmController::class, 'verify']);
        Route::post('/umkm/{id}/delete', [UmkmController::class, 'destroy']);
    });

    Route::middleware('role:pemerintah')->group(function () {
        Route::get('/pemerintah/dashboard', [DashboardController::class, 'index']);
    });

    Route::middleware('role:admin,pemerintah')->group(function () {
        Route::get('/admin/transaksi', [OrderController::class, 'adminOrders']);
    });

    Route::middleware('role:admin,pemerintah,umkm')->group(function () {
        Route::get('/umkm', [UmkmController::class, 'index']);
    });

    Route::middleware('role:admin,umkm')->group(function () {
        Route::get('/umkm/create', [UmkmController::class, 'create']);
        Route::post('/umkm/store', [UmkmController::class, 'store']);
        Route::get('/umkm/{id}/edit', [UmkmController::class, 'edit']);
        Route::post('/umkm/{id}/update', [UmkmController::class, 'update']);
    });

    Route::middleware('role:umkm')->group(function () {
        Route::get('/umkm/dashboard', [DashboardController::class, 'index']);
        Route::get('/umkm/transaksi', [ProductController::class, 'transactions']);
        Route::get('/umkm/produk', [ProductController::class, 'umkmProducts']);
        Route::get('/umkm/produk/create', [ProductController::class, 'create']);
        Route::post('/umkm/produk/store', [ProductController::class, 'store']);
        Route::get('/umkm/produk/{id}/edit', [ProductController::class, 'edit']);
        Route::post('/umkm/produk/{id}/update', [ProductController::class, 'update']);
        Route::post('/umkm/produk/{id}/delete', [ProductController::class, 'destroy']);
    });
});
