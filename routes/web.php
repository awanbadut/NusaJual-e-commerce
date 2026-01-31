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
use App\Http\Controllers\LandingPage\PaymentController as BuyerPaymentController;
use App\Http\Controllers\LandingPage\ProfileController as BuyerProfileController;
use App\Http\Controllers\LandingPage\LocationController;

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
| API LOCATION ROUTES (PUBLIC ACCESS)
|--------------------------------------------------------------------------
| Move this OUTSIDE any middleware group so both Buyers and Sellers can use it.
*/
Route::prefix('api/location')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces']);
    Route::get('/cities/{provinceCode}', [LocationController::class, 'getCities']);
    Route::get('/districts/{cityCode}', [LocationController::class, 'getDistricts']);
    Route::get('/villages/{districtCode}', [LocationController::class, 'getVillages']);
});


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

    // 5. Profile Pembeli
    Route::get('/profile', [BuyerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [BuyerProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/address', [BuyerProfileController::class, 'address'])->name('profile.address');
    // Route::post('/profile/address', [BuyerProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::post('/logout', [BuyerAuthController::class, 'logout'])->name('logout');

    Route::prefix('profile/address')->name('profile.address.')->group(function () {
        Route::post('/', [BuyerProfileController::class, 'storeAddress'])->name('store');
        Route::put('/{address}', [BuyerProfileController::class, 'updateAddress'])->name('update'); // Buat method updateAddress nanti
        Route::delete('/{address}', [BuyerProfileController::class, 'destroyAddress'])->name('destroy'); // Buat method destroyAddress nanti
        Route::patch('/{address}/primary', [BuyerProfileController::class, 'setPrimaryAddress'])->name('setPrimary'); // Buat method setPrimaryAddress nanti
    });
    Route::get('/profile/orders', [BuyerProfileController::class, 'orders'])->name('profile.orders');
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
| ADMIN AUTH ROUTES (Login Terpisah)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'showLoginForm'])
            ->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'login'])
            ->name('login.submit');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Logout
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'logout'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Mitra Management
    Route::get('/mitra', [App\Http\Controllers\Admin\MitraController::class, 'index'])
        ->name('mitra.index');
    Route::get('/mitra/{id}', [App\Http\Controllers\Admin\MitraController::class, 'show'])
        ->name('mitra.show');

    // Payment Verification
    Route::post('/payments/{id}/confirm', [App\Http\Controllers\Admin\PaymentController::class, 'confirm'])
        ->name('payments.confirm');
    Route::post('/payments/{id}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])
        ->name('payments.reject');

    // Withdrawal Management
    Route::post('/withdrawals/{id}/approve', [App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])
        ->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/process', [App\Http\Controllers\Admin\WithdrawalController::class, 'process'])
        ->name('withdrawals.process');
    Route::post('/withdrawals/{id}/reject', [App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])
        ->name('withdrawals.reject');
    Route::get('/withdrawals/{id}/print', [App\Http\Controllers\Admin\WithdrawalController::class, 'print'])
        ->name('withdrawals.print');
});
