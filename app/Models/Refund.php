<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'order_amount',
        'admin_fee',
        'refund_amount',
        'bank_name',
        'account_number',
        'account_holder',
        'status',
        'cancellation_reason',
        'rejection_reason',
        'admin_notes',
        'refund_proof',
        'requested_at',
        'approved_at',
        'processed_at',
        'rejected_at',
        'processed_by',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Calculate admin fee (5% dari total order)
     */
    public static function calculateAdminFee(float $orderAmount): float
    {
        return $orderAmount * 0.05; // 5%
    }

    /**
     * Calculate net refund (order amount - admin fee)
     */
    public static function calculateRefundAmount(float $orderAmount): float
    {
        $adminFee = self::calculateAdminFee($orderAmount);
        return $orderAmount - $adminFee;
    }

    /**
     * Get refund number (formatted ID)
     */
    public function getRefundNumberAttribute(): string
    {
        return 'REF-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
