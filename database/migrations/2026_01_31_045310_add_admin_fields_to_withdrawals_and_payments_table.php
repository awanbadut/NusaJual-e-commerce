<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'withdrawal_proof')) {
                $table->string('withdrawal_proof')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('withdrawals', 'notes')) {
                $table->text('notes')->nullable()->after('withdrawal_proof');
            }
            if (!Schema::hasColumn('withdrawals', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }
        });

        // Tambahan untuk payments (admin yang konfirmasi)
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('payments', 'confirmed_by')) {
                $table->foreignId('confirmed_by')->nullable()->after('admin_notes')->constrained('users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['withdrawal_proof', 'notes', 'admin_notes']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'confirmed_by']);
        });
    }
};
