<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'bank_account_id',
        'amount',
        'admin_fee',
        'total_received',
        'notes',
        'admin_notes',
        'status',
        'requested_at',
        'processed_at',
        'withdrawal_proof',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_received' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // ✅ BIAYA ADMIN FLAT RP 10.000
    const ADMIN_FEE_FLAT = 10000;

    /**
     * Calculate admin fee (FLAT Rp 10.000)
     */
    public static function calculateAdminFee($amount)
    {
        return self::ADMIN_FEE_FLAT;
    }

    /**
     * Calculate total received by seller
     */
    public static function calculateTotalReceived($amount, $adminFee = null)
    {
        if ($adminFee === null) {
            $adminFee = self::calculateAdminFee($amount);
        }
        
        return $amount - $adminFee;
    }

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
