<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'status',          // enum: pending, processing, etc
        'payment_status',  // enum: unpaid, paid, etc
        'payment_method',  // bank_transfer, etc
        'sub_total',
        'tax',
        'shipping_cost',
        'total_amount',
        'shipping_address',
        'notes',

        // [TAMBAHAN] Sesuai struktur tabel database kamu yang baru
        'recipient_name',
        'recipient_phone',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ===========================
    // RELATIONSHIPS
    // ===========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * [BARU] Relasi ke tabel Payments
     * Satu Order memiliki Satu Data Pembayaran
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // ===========================
    // HELPERS & ACCESSORS
    // ===========================

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->attributes['sub_total'] ?? 0;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
