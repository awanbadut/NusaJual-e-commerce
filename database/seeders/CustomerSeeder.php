<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get store penjual
        $store = Store::first();
        
        if (!$store) {
            $this->command->error('❌ Store belum ada! Buat user seller dulu.');
            return;
        }

        // Get products from store
        $products = Product::where('store_id', $store->id)->get();
        
        if ($products->count() == 0) {
            $this->command->error('❌ Produk belum ada! Buat produk dulu.');
            return;
        }

        $this->command->info('🔄 Membuat 20 customers dengan order...');

        // Create 20 customers
        for ($i = 1; $i <= 20; $i++) {
            // Create customer
            $customer = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'phone' => $faker->phoneNumber(),
                'date_of_birth' => $faker->date('Y-m-d', '-25 years'),
                'address' => $faker->address(),
                'role' => 'buyer',
            ]);

            // Create random number of orders (3-10 orders per customer)
            $orderCount = rand(3, 10);
            
            for ($j = 1; $j <= $orderCount; $j++) {
                $status = $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']);
                $shippingCost = rand(10000, 50000);
                
                // Create order
                $order = Order::create([
                    'user_id' => $customer->id,
                    'store_id' => $store->id,
                    'order_number' => 'NB-' . strtoupper(uniqid()),
                    'sub_total' => 0,
                    'shipping_cost' => $shippingCost,
                    'total_amount' => 0,
                    'status' => $status,
                    'shipping_address' => $customer->address,
                    'recipient_name' => $customer->name,
                    'recipient_phone' => $customer->phone,
                    'created_at' => now()->subDays(rand(1, 180)),
                ]);

                // Add 1-5 products to order
                $itemCount = rand(1, 5);
                $subtotal = 0;

                for ($k = 1; $k <= $itemCount; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);
                    $price = $product->price;
                    $itemTotal = $price * $quantity;
                    $subtotal += $itemTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $itemTotal, // Pakai 'total' bukan 'sub_total'
                    ]);
                }

                // Update order totals
                $order->update([
                    'sub_total' => $subtotal,
                    'total_amount' => $subtotal + $shippingCost,
                ]);
            }

            $this->command->info("✅ Customer #{$i}: {$customer->name} - {$orderCount} orders");
        }

        $this->command->info('✨ Selesai! 20 customers dengan orders berhasil dibuat!');
    }
}
