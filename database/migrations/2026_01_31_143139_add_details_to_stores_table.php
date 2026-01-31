<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('stores', 'owner_name')) {
                $table->string('owner_name')->nullable()->after('store_name');
            }
            if (!Schema::hasColumn('stores', 'phone')) {
                $table->string('phone')->nullable()->after('owner_name');
            }

            // Kolom Bank
            if (!Schema::hasColumn('stores', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('address');
                $table->string('account_number')->nullable()->after('bank_name');
                $table->string('account_holder')->nullable()->after('account_number');
            }
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'phone', 'bank_name', 'account_number', 'account_holder']);
        });
    }
};
