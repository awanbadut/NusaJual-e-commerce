<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Refund Amount Details
            $table->decimal('order_amount', 15, 2); // Total pesanan asli
            $table->decimal('admin_fee', 15, 2)->default(0); // Biaya admin (5%)
            $table->decimal('refund_amount', 15, 2); // Jumlah yang dikembalikan (setelah potong admin)
            
            // Bank Account Info (from buyer)
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_holder', 255);
            
            // Status & Reasons
            $table->enum('status', ['pending', 'approved', 'processed', 'rejected'])->default('pending');
            $table->text('cancellation_reason')->nullable(); // Alasan buyer
            $table->text('rejection_reason')->nullable(); // Alasan admin reject
            $table->text('admin_notes')->nullable(); // Catatan admin
            
            // Refund Proof (uploaded by admin)
            $table->string('refund_proof')->nullable(); // Bukti transfer dari admin
            
            // Timestamps
            $table->timestamp('requested_at')->nullable(); // Kapan buyer request
            $table->timestamp('approved_at')->nullable(); // Kapan admin approve
            $table->timestamp('processed_at')->nullable(); // Kapan admin upload bukti transfer
            $table->timestamp('rejected_at')->nullable(); // Kapan admin reject
            
            // Admin who processed
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
