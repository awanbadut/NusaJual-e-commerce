<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('refund_status', ['none', 'pending', 'approved', 'processed', 'rejected'])
                  ->default('none')
                  ->after('cancelled_at');
            $table->decimal('refund_amount', 15, 2)->nullable()->after('refund_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_status', 'refund_amount']);
        });
    }
};
