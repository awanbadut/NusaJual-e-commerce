<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kopi', 'slug' => 'kopi', 'description' => 'Berbagai jenis kopi pilihan'],
            ['name' => 'Teh', 'slug' => 'teh', 'description' => 'Berbagai jenis teh berkualitas'],
            ['name' => 'Sawit', 'slug' => 'sawit', 'description' => 'Produk kelapa sawit'],
            ['name' => 'Rempah', 'slug' => 'rempah', 'description' => 'Rempah-rempah nusantara'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
