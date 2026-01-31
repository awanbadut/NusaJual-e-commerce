<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Store;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share data mitra ke semua layout admin
        View::composer('layouts.admin', function ($view) {
            // 5 mitra pertama untuk dropdown sidebar
            $sidebarStores = Store::orderBy('store_name')->take(5)->get(['id', 'store_name']);
            
            // Semua mitra untuk modal (kalau butuh nanti)
            $allSidebarStores = Store::orderBy('store_name')->get(['id', 'store_name']);

            $view->with([
                'sidebarStores' => $sidebarStores,
                'allSidebarStores' => $allSidebarStores
            ]);
        });
    }
}

