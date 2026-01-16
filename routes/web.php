<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SellerAuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\SalesController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProfileController; // ← TAMBAH INI

// Redirect root ke seller login
Route::get('/', function () {
    return redirect()->route('seller.login');
});

// ============================================
// AUTH ROUTES UNTUK PENJUAL & ADMIN
// ============================================
Route::prefix('seller')->name('seller.')->group(function () {
    
    // Guest routes (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [SellerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SellerAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [SellerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [SellerAuthController::class, 'register'])->name('register.submit');
    });

    // Protected routes (harus login sebagai seller)
    Route::middleware(['auth', 'seller'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Products
        Route::resource('products', ProductController::class);
        Route::delete('/products/{id}/image', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        
        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
        
        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Sales
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/sales/export', [SalesController::class, 'export'])->name('sales.export');
        
        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/withdrawal', [PaymentController::class, 'requestWithdrawal'])->name('payments.withdrawal');
        
        // Profile - PERBAIKI DI SINI (hapus seller. di name karena sudah ada prefix)
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile/store', [ProfileController::class, 'updateStore'])->name('profile.update-store');
        Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update-address');
        Route::put('/profile/bank', [ProfileController::class, 'updateBankAccount'])->name('profile.update-bank');
        Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        
        // Logout
        Route::post('/logout', [SellerAuthController::class, 'logout'])->name('logout');
    });
});

// ============================================
// ADMIN ROUTES (Coming Soon)
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - Coming Soon';
    })->name('dashboard');
});
