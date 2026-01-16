<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            // DemoDataSeeder akan dipanggil manual setelah register seller
        ]);
    }
}
