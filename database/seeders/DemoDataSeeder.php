<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // PENTING: Import Str untuk slug

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cek Toko, kalau belum ada => Buat Otomatis
        $store = Store::first();

        if (!$store) {
            $this->command->info('🏭 Toko belum ada. Membuat Seller & Toko baru...');

            // Buat User Seller
            $seller = User::create([
                'name' => 'Seller Utama',
                'email' => 'seller@nusa.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'phone' => '081299998888',
                // 'address' => 'Jl. Seller No. 1 Padang', // Opsional, karena alamat utama ada di stores/addresses
            ]);

            // Buat Toko (SESUAIKAN DENGAN STRUKTUR BARU)
            $store = Store::create([
                'user_id'       => $seller->id,
                'store_name'    => 'Nusa Official Store',
                'slug'          => Str::slug('Nusa Official Store'), // Wajib ada
                'description'   => 'Toko resmi Nusa Belanja terlengkap.', // Wajib ada (sesuai error tadi)

                // Data Wilayah Lengkap (Dummy Data Padang)
                'province_code' => '13',
                'province'      => 'Sumatera Barat',
                'city_code'     => '1371',
                'city'          => 'Kota Padang',
                'district_code' => '137101',
                'district'      => 'Padang Utara',
                'village_code'  => '1371011001', // Kode dummy 10 digit (Penting utk ongkir)
                'village'       => 'Air Tawar',

                'postal_code'   => '25100',
                'address'       => 'Jl. Khatib Sulaiman No. 1',
                'logo'          => null,
            ]);
        }

        // 2. Buat User Pembeli Dummy (John Smith)
        $buyer = User::where('email', 'buyer@test.com')->first();
        if (!$buyer) {
            $buyer = User::create([
                'name' => 'John Smith',
                'email' => 'buyer@test.com',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'phone' => '+62 812 3456 7890',
                // 'address' => 'Jl. Contoh No. 123', // Hapus jika error, gunakan tabel addresses
            ]);
        }

        // 3. Buat Produk Dummy
        $productsData = [
            [
                'name' => 'Kopi Gayo Premium',
                'description' => 'Kopi Arabika Gayo asli dengan aroma kuat.',
                'price' => 200000,
                'stock' => 50,
                'unit' => 'Kg',
                'category_id' => 1,
            ],
            [
                'name' => 'Teh Kayu Aro',
                'description' => 'Teh hitam kualitas ekspor dari Kerinci.',
                'price' => 50000,
                'stock' => 100,
                'unit' => 'Kotak',
                'category_id' => 2,
            ],
            [
                'name' => 'Minyak Goreng Sawit',
                'description' => 'Minyak goreng jernih 2 kali penyaringan.',
                'price' => 18000,
                'stock' => 200,
                'unit' => 'Liter',
                'category_id' => 3,
            ],
        ];

        foreach ($productsData as $data) {
            // Cek biar produk gak dobel
            if (Product::where('name', $data['name'])->exists()) {
                continue;
            }

            $product = Product::create([
                'store_id' => $store->id,
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                // 'slug' => ... (Dihapus sesuai permintaan sebelumnya)
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'unit' => $data['unit'],
                'status' => 'active',
            ]);

            // Buat gambar dummy
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/dummy.jpg',
                'is_primary' => true,
            ]);
        }

        $this->command->info('✅ Produk, Toko & User berhasil dibuat!');
    }
}
