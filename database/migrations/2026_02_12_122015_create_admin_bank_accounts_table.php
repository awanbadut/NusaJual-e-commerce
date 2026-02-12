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
        Schema::create('admin_bank_accounts', function (Blueprint $table) {
            $table->id();
            // Mengubah string menjadi enum
            $table->enum('bank_name', [
                'BCA',
                'BNI',
                'BRI',
                'Mandiri',
                'BSI',
                'CIMB Niaga',
                'Danamon',
                'Permata',
                'Maybank',
                'BTN'
            ]);
            $table->string('account_number');
            $table->string('account_holder');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_bank_accounts');
    }
};
