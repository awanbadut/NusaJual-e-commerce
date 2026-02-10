<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Add admin_fee column if not exists
            if (!Schema::hasColumn('withdrawals', 'admin_fee')) {
                $table->decimal('admin_fee', 15, 2)->default(10000)->after('amount');
            }
            
            // Add total_received column if not exists
            if (!Schema::hasColumn('withdrawals', 'total_received')) {
                $table->decimal('total_received', 15, 2)->after('admin_fee');
            }
            
            // Add notes for seller
            if (!Schema::hasColumn('withdrawals', 'notes')) {
                $table->text('notes')->nullable()->after('total_received');
            }
            
            // Add admin notes
            if (!Schema::hasColumn('withdrawals', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }
            
            // Add withdrawal proof (bukti transfer dari admin)
            if (!Schema::hasColumn('withdrawals', 'withdrawal_proof')) {
                $table->string('withdrawal_proof')->nullable()->after('admin_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $columns = ['admin_fee', 'total_received', 'notes', 'admin_notes', 'withdrawal_proof'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('withdrawals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
