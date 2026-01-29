<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('receiver_name');
            $table->string('phone');

            // Data Wilayah (Simpan Kode & Nama)
            $table->string('province_code'); // Contoh: 11
            $table->string('province_name');
            $table->string('city_code');     // Contoh: 1101 (Kabupaten/Kota)
            $table->string('city_name');
            $table->string('district_code'); // Contoh: 110101 (Kecamatan)
            $table->string('district_name');
            $table->string('village_code');  // PENTING: 10 Digit (1101010001) untuk Cek Ongkir
            $table->string('village_name');
            $table->string('postal_code');   // Kode pos otomatis dari API

            $table->text('detail_address'); // Jalan, No Rumah, Patokan
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
