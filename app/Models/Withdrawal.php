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
        'withdrawal_proof',
        'notes',
        'admin_notes',
        'status',
        'requested_at',
        'processed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_received' => 'decimal:2',
    ];

    /**
     * Calculate admin fee
     */
    public static function calculateAdminFee($amount)
    {
        $fixedFee = config('withdrawal.admin_fee_fixed', 5000);
        $percentageFee = ($amount * config('withdrawal.admin_fee_percentage', 1)) / 100;
        
        // Total fee = fixed + percentage
        return $fixedFee + $percentageFee;
    }

    /**
     * Calculate total received (amount - admin fee)
     */
    public static function calculateTotalReceived($amount, $adminFee)
    {
        return $amount - $adminFee;
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
