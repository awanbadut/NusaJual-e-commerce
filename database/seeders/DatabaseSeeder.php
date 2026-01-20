<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil berurutan tanpa syarat
        $this->call([
            CategorySeeder::class, // 1. Bikin Kategori
            DemoDataSeeder::class, // 2. Bikin Toko, User, Produk
            CustomerSeeder::class, // 3. Bikin 20 Customer & Order
        ]);
    }
}
