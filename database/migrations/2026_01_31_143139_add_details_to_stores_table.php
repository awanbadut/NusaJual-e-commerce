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

           
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'phone']);
        });
    }
};
