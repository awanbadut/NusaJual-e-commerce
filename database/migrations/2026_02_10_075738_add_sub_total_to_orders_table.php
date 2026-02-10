<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add sub_total column (subtotal produk tanpa ongkir)
            if (!Schema::hasColumn('orders', 'sub_total')) {
                $table->decimal('sub_total', 15, 2)->default(0)->after('order_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'sub_total')) {
                $table->dropColumn('sub_total');
            }
        });
    }
};
