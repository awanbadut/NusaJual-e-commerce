<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/katalog', function () {
    return view('catalog');
})->name('katalog');

Route::get('/detail-produk', function () {
    return view('produkDetail');
})->name('detail-produk');

Route::get('/profil-mitra', function () {
    return view('profilMitra');
})->name('profil-mitra');

Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');

// Redirect halaman login utama ke pembeli secara default
Route::get('/login', function () {
    return redirect('/login/pembeli');
});

// Satu Route untuk menangani kedua peran
Route::get('/login/{role}', function ($role) {
    if (!in_array($role, ['pembeli', 'penjual'])) {
        abort(404);
    }
    return view('auth.login', ['role' => $role]);
})->name('login');

Route::get('/register-penjual', function () {
    return view('auth.register');
})->name('register.penjual');
