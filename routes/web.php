<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SellerAuthController;
// landing page controllers 
use App\Http\Controllers\LandingPage\HomeController as PublicHomeController;
use App\Http\Controllers\LandingPage\ProductController as PublicProductController;
use App\Http\Controllers\LandingPage\StoreController as PublicStoreController;
use App\Http\Controllers\LandingPage\CartController as PublicCartController;

use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\SalesController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProfileController;

// ============================================
// CUSTOMER ROUTES (dari Cipah)
// ============================================
Route::get('/', [PublicHomeController::class, 'index'])->name('home');

Route::get('/katalog', [PublicProductController::class, 'index'])->name('katalog');
Route::get('/produk/{id}', [PublicProductController::class, 'show'])->name('produk.show');
Route::get('/mitra/{id}', [PublicStoreController::class, 'show'])->name('profil-mitra');

// Arahkan ke Controller method index
Route::get('/keranjang', [PublicCartController::class, 'index'])->name('keranjang');
// Tambah ke keranjang
Route::post('/keranjang', [PublicCartController::class, 'store'])->name('keranjang.store');
// Route pendukung lainnya (sudah benar jika kamu copas sebelumnya)
Route::delete('/keranjang/clear', [PublicCartController::class, 'clear'])->name('keranjang.clear');
Route::patch('/keranjang/{id}', [PublicCartController::class, 'update'])->name('keranjang.update');
Route::delete('/keranjang/{id}', [PublicCartController::class, 'destroy'])->name('keranjang.destroy');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/payment', function () {
    return view('payment');
})->name('payment');
Route::get('/payment/succes', function () {
    return view('paymentSucces');
})->name('success');


// ============================================
// LOGIN ROUTES - HYBRID (Cipah + Dev-Sawan)
// ============================================

// Redirect default login ke pembeli
Route::get('/login', function () {
    return redirect('/login/pembeli');
});

// Login pembeli (pakai view Cipah untuk sekarang)
Route::get('/login/pembeli', function () {
    return view('auth.login', ['role' => 'pembeli']);
})->name('login.pembeli');

// Login penjual (redirect ke sistem kamu yang proper)
Route::get('/login/penjual', function () {
    return redirect()->route('seller.login');
})->name('login.penjual');

// Register penjual (redirect ke sistem kamu)
Route::get('/register-penjual', function () {
    return redirect()->route('seller.register');
})->name('register.penjual');

// ============================================
// SELLER ROUTES (dari Dev-Sawan)
// ============================================
Route::prefix('seller')->name('seller.')->group(function () {

    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [SellerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SellerAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [SellerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [SellerAuthController::class, 'register'])->name('register.submit');
    });

    // Protected routes
    Route::middleware(['auth', 'seller'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::delete('/products/{id}/image', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/sales/export', [SalesController::class, 'export'])->name('sales.export');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/withdrawal', [PaymentController::class, 'requestWithdrawal'])->name('payments.withdrawal');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile/store', [ProfileController::class, 'updateStore'])->name('profile.update-store');
        Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update-address');
        Route::put('/profile/bank', [ProfileController::class, 'updateBankAccount'])->name('profile.update-bank');
        Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/logout', [SellerAuthController::class, 'logout'])->name('logout');
    });
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - Coming Soon';
    })->name('dashboard');
});
