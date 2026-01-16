<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user pembeli dummy
        $buyer = User::create([
            'name' => 'John Smith',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'phone' => '+63 812 3456 7890',
            'address' => 'Jl. Contoh No. 123',
        ]);

        // Ambil store dari seller yang sudah daftar
        $store = Store::first();
        
        if (!$store) {
            $this->command->error('Tidak ada toko! Silakan register sebagai seller dulu.');
            return;
        }

        // Buat produk dummy
        $products = [
            [
                'name' => 'Egestas vehicula',
                'description' => 'Kopi premium pilihan dengan rasa yang kaya',
                'price' => 200000,
                'stock' => 3,
                'unit' => 'Kg',
                'category_id' => 1,
            ],
            [
                'name' => 'Amet purus',
                'description' => 'Kopi berkualitas tinggi untuk pecinta kopi',
                'price' => 250000,
                'stock' => 2,
                'unit' => 'Kg',
                'category_id' => 1,
            ],
            [
                'name' => 'Vulputate egestas',
                'description' => 'Kopi arabika pilihan',
                'price' => 150000,
                'stock' => 45,
                'unit' => 'Kg',
                'category_id' => 1,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'store_id' => $store->id,
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'unit' => $productData['unit'],
                'status' => 'active',
            ]);

            // Buat gambar dummy
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/dummy.jpg',
                'is_primary' => true,
            ]);
        }

        // Buat pesanan dummy
        $statuses = ['completed', 'processing', 'pending', 'shipped'];
        
        for ($i = 0; $i < 10; $i++) {
            $order = Order::create([
                'user_id' => $buyer->id,
                'store_id' => $store->id,
                'order_number' => 'NB-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'sub_total' => 1000000,
                'shipping_cost' => 1000,
                'total_amount' => 1001000,
                'status' => $statuses[array_rand($statuses)],
                'shipping_address' => 'Mulberry Street, Adamsbury 27378-5715, 53144 Swaniawski Key, Huntington Beach 23654',
                'recipient_name' => 'Theresa Lebsack',
                'recipient_phone' => '748-511-5598',
            ]);

            // Buat order items
            $product = Product::inRandomOrder()->first();
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => $product->price,
                'total' => $product->price * 2,
            ]);

            // Buat payment
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'status' => in_array($order->status, ['completed', 'shipped']) ? 'confirmed' : 'pending',
                'paid_at' => now(),
                'confirmed_at' => in_array($order->status, ['completed', 'shipped']) ? now() : null,
            ]);
        }

        $this->command->info('Demo data berhasil dibuat!');
    }
}
