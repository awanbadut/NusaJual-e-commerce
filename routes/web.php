<?php

use App\Http\Controllers\Auth\BuyerAuthController;
use Illuminate\Support\Facades\Route;

// --- CONTROLLERS IMPORT ---

// 1. Auth Controllers
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\SellerAuthController;

// 2. Landing Page / Buyer Controllers (Saya rapikan aliasnya)
use App\Http\Controllers\LandingPage\HomeController as PublicHomeController;
use App\Http\Controllers\LandingPage\ProductController as PublicProductController;
use App\Http\Controllers\LandingPage\StoreController as PublicStoreController;
use App\Http\Controllers\LandingPage\CartController as PublicCartController;
use App\Http\Controllers\LandingPage\CheckoutController as PublicCheckoutController;
use App\Http\Controllers\LandingPage\PaymentController as BuyerPaymentController; // Alias diperbaiki

// 3. Seller Controllers
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\SalesController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProfileController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest)
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [PublicHomeController::class, 'index'])->name('home');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Login & Register Redirects
Route::get('/login', function () {
    return redirect('/login/pembeli');
})->name('login');

Route::get('/login/pembeli', function () {
    return view('auth.login', ['role' => 'pembeli']);
})->name('login.pembeli');

Route::get('/login/penjual', function () {
    return redirect()->route('seller.login');
})->name('login.penjual');

Route::get('/register-penjual', function () {
    return redirect()->route('seller.register');
})->name('register.penjual');


/*
|--------------------------------------------------------------------------
| BUYER ROUTES (Authenticated User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'buyer'])->group(function () {

    // 1. Katalog & Detail Produk
    Route::get('/katalog', [PublicProductController::class, 'index'])->name('katalog');
    Route::get('/produk/{id}', [PublicProductController::class, 'show'])->name('produk.show');
    Route::get('/mitra/{id}', [PublicStoreController::class, 'show'])->name('profil-mitra');

    // 2. Keranjang (Cart)
    Route::get('/keranjang', [PublicCartController::class, 'index'])->name('keranjang');
    Route::post('/keranjang', [PublicCartController::class, 'store'])->name('keranjang.store');
    Route::delete('/keranjang/clear', [PublicCartController::class, 'clear'])->name('keranjang.clear');
    Route::patch('/keranjang/{id}', [PublicCartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [PublicCartController::class, 'destroy'])->name('keranjang.destroy');

    // 3. Checkout
    // Halaman Review (Terima GET & POST)
    Route::match(['get', 'post'], '/checkout/review', [PublicCheckoutController::class, 'review'])->name('checkout.review');
    // Proses Simpan Order
    Route::post('/checkout/process', [PublicCheckoutController::class, 'store'])->name('checkout.store');

    // 4. Payment (PERBAIKAN DISINI)
    // PENTING: Route yang spesifik (/success/{id}) harus ditaruh SEBELUM route dinamis (/{id})
    // Agar laravel tidak mengira kata "success" adalah sebuah ID.

    Route::get('/payment/success/{id}', [BuyerPaymentController::class, 'success'])->name('payment.success');

    Route::get('/payment/{id}', [BuyerPaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{id}', [BuyerPaymentController::class, 'process'])->name('payment.process');

    Route::post('/logout', [BuyerAuthController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| SELLER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->name('seller.')->group(function () {

    // Guest routes (Login/Register)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [SellerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SellerAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [SellerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [SellerAuthController::class, 'register'])->name('register.submit');
    });

    // Protected routes (Dashboard dll)
    Route::middleware(['auth', 'seller'])->group(function () {
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

        // Payments (Seller)
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/withdrawal', [PaymentController::class, 'requestWithdrawal'])->name('payments.withdrawal');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile/store', [ProfileController::class, 'updateStore'])->name('profile.update-store');
        Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update-address');
        Route::put('/profile/bank', [ProfileController::class, 'updateBankAccount'])->name('profile.update-bank');
        Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

        // Logout
        Route::post('/logout', [SellerAuthController::class, 'logout'])->name('logout');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - Coming Soon';
    })->name('dashboard');
});
