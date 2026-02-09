<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Tambah kolom tanpa after() - biar gak error
            if (!Schema::hasColumn('stores', 'province_code')) {
                $table->string('province_code')->nullable();
            }
            if (!Schema::hasColumn('stores', 'province')) {
                $table->string('province')->nullable();
            }
            if (!Schema::hasColumn('stores', 'city_code')) {
                $table->string('city_code')->nullable();
            }
            if (!Schema::hasColumn('stores', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('stores', 'district_code')) {
                $table->string('district_code')->nullable();
            }
            if (!Schema::hasColumn('stores', 'district')) {
                $table->string('district')->nullable();
            }
            if (!Schema::hasColumn('stores', 'village_code')) {
                $table->string('village_code')->nullable();
            }
            if (!Schema::hasColumn('stores', 'village')) {
                $table->string('village')->nullable();
            }
            if (!Schema::hasColumn('stores', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('stores', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('stores', 'owner_name')) {
                $table->string('owner_name')->nullable();
            }
            if (!Schema::hasColumn('stores', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('stores', 'logo')) {
                $table->string('logo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $columns = [
                'province_code', 'province', 
                'city_code', 'city', 
                'district_code', 'district', 
                'village_code', 'village', 
                'postal_code', 'address', 
                'owner_name', 'phone', 'logo'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('stores', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
