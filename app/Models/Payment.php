<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'disbursement_date',
        'transaction_date',
        'status',
        'proof_image',
        'notes',
    ];

    protected $casts = [
        'disbursement_date' => 'date',
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
