<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom tracking
            $table->string('tracking_number')->nullable()->after('recipient_phone');
            $table->string('courier')->nullable()->after('tracking_number');
            
            // Tambah timestamps untuk tracking
            $table->timestamp('shipped_at')->nullable()->after('courier');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            
            // Tambah payment_method jika belum ada
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('status');
            }
        });
        
        Schema::table('payments', function (Blueprint $table) {
            // Tambah kolom rejection
            $table->timestamp('rejected_at')->nullable()->after('confirmed_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number', 
                'courier', 
                'shipped_at', 
                'delivered_at'
            ]);
            
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'rejected_at',
                'rejected_by',
                'rejection_reason'
            ]);
        });
    }
};
