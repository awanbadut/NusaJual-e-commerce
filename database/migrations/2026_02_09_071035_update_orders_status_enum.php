<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update ENUM status di tabel orders
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'confirmed', 'processing', 'packing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        // Rollback ke ENUM lama
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
