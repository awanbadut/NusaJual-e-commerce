<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('admin_fee', 15, 2)->default(0)->after('amount');
            $table->decimal('total_received', 15, 2)->default(0)->after('admin_fee');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['admin_fee', 'total_received']);
        });
    }
};
