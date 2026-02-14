<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'status',
        'payment_status',
        'payment_method',
        'sub_total',
        'tax',
        'shipping_cost',
        'total_amount',
        'shipping_address',
        'notes',
        'recipient_name',
        'recipient_phone',
        'tracking_number',
        'courier',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'refund_status',      // NEW
        'refund_amount',      // NEW
    ];

     public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    // ==========================================
    // STATUS HELPERS
    // ==========================================

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isConfirmed()
    {
        return $this->payment_status === 'confirmed';
    }

    public function isPacking()
    {
        return $this->status === 'packing';
    }

    public function getStatusLabel()
    {
        if ($this->status === 'cancelled') return 'Pesanan Dibatalkan';
        if ($this->status === 'completed') return 'Pesanan Selesai';

        // Logic berdasarkan Payment
        if (!$this->payment || $this->payment->status === 'pending') {
            return 'Menunggu Pembayaran';
        }

        if ($this->payment->status === 'paid') {
            return 'Menunggu Konfirmasi Admin';
        }

        if ($this->payment->status === 'confirmed') {
            if ($this->status === 'processing') return 'Pesanan Dikonfirmasi';
            if ($this->status === 'packing') return 'Pesanan Sedang Dikemas';
            if ($this->status === 'shipped') return 'Pesanan Dalam Pengiriman';
        }

        return ucfirst($this->status);
    }

    // ==========================================
    // BUSINESS LOGIC - CANCELLATION
    // ==========================================

    /**
     * Cek apakah order bisa dibatalkan
     * Rules:
     * 1. Status = pending (belum bayar) → Bisa dibatalkan kapan saja
     * 2. Status = paid (sudah bayar) → Bisa dibatalkan dalam 2 jam sejak paid_at
     * 3. Status lainnya → Tidak bisa dibatalkan
     */
    public function canBeCancelled(): bool
    {
        // Jika sudah cancelled/completed, tidak bisa dibatalkan lagi
        if (in_array($this->status, ['cancelled', 'completed'])) {
            return false;
        }

        // Jika belum bayar (pending), bisa dibatalkan kapan saja
        if ($this->payment_status === 'pending') {
            return true;
        }

        // Jika sudah bayar, cek apakah masih dalam 2 jam
        if ($this->payment_status === 'paid' && $this->payment && $this->payment->paid_at) {
            $hoursSincePaid = $this->payment->paid_at->diffInHours(now());
            return $hoursSincePaid < 2;
        }

        // Jika sudah confirmed/processing/shipped, tidak bisa dibatalkan
        return false;
    }

    /**
     * Get remaining time untuk cancel (dalam menit)
     */
    public function getCancelTimeRemaining(): ?int
    {
        if (!$this->payment || !$this->payment->paid_at || $this->payment_status !== 'paid') {
            return null;
        }

        $hoursSincePaid = $this->payment->paid_at->diffInHours(now());

        if ($hoursSincePaid >= 2) {
            return 0;
        }

        $minutesSincePaid = $this->payment->paid_at->diffInMinutes(now());
        $remainingMinutes = (2 * 60) - $minutesSincePaid;

        return max(0, $remainingMinutes);
    }

    // ==========================================
    // BUSINESS LOGIC - COMPLETION
    // ==========================================

    /**
     * Cek apakah order bisa diselesaikan oleh buyer
     * Rules: Status = shipped (dalam pengiriman)
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'shipped';
    }

    // ==========================================
    // BUSINESS LOGIC - REFUND
    // ==========================================

    /**
     * Cek apakah order memerlukan refund
     */
    public function needsRefund(): bool
    {
        // Hanya order yang sudah dibayar yang perlu refund
        return $this->payment_status === 'paid' && $this->status === 'cancelled';
    }
}
