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
    'payment_proof',
    'status',
    'paid_at',
    'confirmed_at',
    'admin_notes',
    'confirmed_by',
];


    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke admin yang konfirmasi
    public function confirmedBy()
{
    return $this->belongsTo(User::class, 'confirmed_by');
}


    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
