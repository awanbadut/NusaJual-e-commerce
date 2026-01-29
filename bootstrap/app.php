<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <--- 1. JANGAN LUPA TAMBAHKAN INI

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'seller' => \App\Http\Middleware\EnsureSeller::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'buyer'  => \App\Http\Middleware\EnsureBuyer::class,
        ]);

        // 2. UBAH BAGIAN INI (Ganti satu baris lama dengan blok ini)
        $middleware->redirectGuestsTo(function (Request $request) {
            // Jika user akses URL yang berawalan 'seller/'
            if ($request->is('seller/*')) {
                return route('seller.login');
            }

            // Default untuk user biasa (akses keranjang, checkout, dll)
            return route('login.pembeli');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
