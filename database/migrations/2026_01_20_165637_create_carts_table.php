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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang beli
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produk apa
            $table->integer('quantity')->default(1); // Jumlahnya
            $table->timestamps();

            // Mencegah duplikasi: Satu user hanya boleh punya satu baris untuk satu produk
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
