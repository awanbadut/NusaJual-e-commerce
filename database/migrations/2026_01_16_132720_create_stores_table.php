<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('store_name');
            $table->string('slug')->unique(); // Bagus untuk URL toko
            $table->text('description')->nullable(); // <--- TAMBAHKAN INI (Penyebab Error)

            // Wilayah (Simpan Kode & Nama)
            $table->string('province_code');
            $table->string('province');
            $table->string('city_code');
            $table->string('city');
            $table->string('district_code');
            $table->string('district');
            $table->string('village_code'); // PENTING UNTUK ONGKIR
            $table->string('village');
            $table->string('postal_code');

            $table->text('address'); // Alamat detail
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
