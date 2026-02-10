<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'slug',
        'province_code',
        'province',
        'city_code',
        'city',
        'district_code',
        'district',
        'village_code',
        'village',
        'postal_code',
        'address',
        'logo',
        'description',
        'owner_name',
        'phone',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // ==========================================
    // WITHDRAWAL HELPER METHODS
    // ==========================================

    /**
     * Get available balance (subtotal produk saja, exclude ongkir)
     * Dana yang bisa ditarik seller
     */
    public function getAvailableBalance()
    {
        $completedOrders = $this->orders()
            ->where('status', 'completed')
            ->get();

        $totalSales = $completedOrders->sum('sub_total'); // ✅ HANYA SUBTOTAL PRODUK

        $totalWithdrawn = $this->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $pendingWithdrawals = $this->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');

        return max(0, $totalSales - $totalWithdrawn - $pendingWithdrawals);
    }

    /**
     * Get total sales from completed orders (subtotal only)
     */
    public function getTotalSales()
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('sub_total');
    }

    /**
     * Get total withdrawn amount (approved + completed)
     */
    public function getTotalWithdrawn()
    {
        return $this->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');
    }

    /**
     * Get pending withdrawal amount
     */
    public function getPendingWithdrawals()
    {
        return $this->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');
    }

    /**
     * Get withdrawable balance (available - pending)
     */
    public function getWithdrawableBalance()
    {
        return max(0, $this->getAvailableBalance() - $this->getPendingWithdrawals());
    }

    /**
     * Check if store can withdraw certain amount
     */
    public function canWithdraw($amount)
    {
        $minAmount = config('withdrawal.minimum_amount', 50000);
        $availableBalance = $this->getWithdrawableBalance();
        
        return $amount >= $minAmount && $amount <= $availableBalance;
    }

    /**
     * Get withdrawal statistics
     */
    public function getWithdrawalStats()
    {
        return [
            'total_sales' => $this->getTotalSales(),
            'total_withdrawn' => $this->getTotalWithdrawn(),
            'pending_withdrawals' => $this->getPendingWithdrawals(),
            'available_balance' => $this->getAvailableBalance(),
            'withdrawable_balance' => $this->getWithdrawableBalance(),
            'completed_orders_count' => $this->orders()->where('status', 'completed')->count(),
            'total_withdrawals_count' => $this->withdrawals()->count(),
        ];
    }

    // ==========================================
    // STORE STATUS HELPERS
    // ==========================================

    /**
     * Check if store has bank account
     */
    public function hasBankAccount()
    {
        return $this->bankAccounts()->exists();
    }

    /**
     * Get primary/first bank account
     */
    public function getPrimaryBankAccount()
    {
        return $this->bankAccounts()->first();
    }

    /**
     * Check if store is verified (has complete data)
     */
    public function isVerified()
    {
        return !empty($this->store_name) 
            && !empty($this->village_code) 
            && $this->hasBankAccount();
    }

    // ==========================================
    // SALES STATISTICS
    // ==========================================

    /**
     * Get sales statistics for dashboard
     */
    public function getSalesStats($startDate = null, $endDate = null)
    {
        $query = $this->orders()->where('status', 'completed');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $orders = $query->get();

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('sub_total'), // Revenue dari produk saja
            'total_shipping' => $orders->sum('shipping_cost'), // Total ongkir (bukan milik seller)
            'average_order_value' => $orders->count() > 0 ? $orders->avg('sub_total') : 0,
        ];
    }

    /**
     * Get monthly sales (for chart)
     */
    public function getMonthlySales($year = null)
    {
        $year = $year ?? now()->year;

        return $this->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(sub_total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    // ==========================================
    // PRODUCT HELPERS
    // ==========================================

    /**
     * Get active products count
     */
    public function getActiveProductsCount()
    {
        return $this->products()->where('status', 'active')->count();
    }

    /**
     * Get out of stock products count
     */
    public function getOutOfStockProductsCount()
    {
        return $this->products()->where('stock', 0)->count();
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address,
            $this->village,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ]));
    }

    /**
     * Get logo URL (with fallback)
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        // Default logo (bisa pakai UI Avatars atau placeholder)
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->store_name) . '&size=200&background=0F4C20&color=fff';
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope untuk store yang verified
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('village_code')
                    ->whereHas('bankAccounts');
    }

    /**
     * Scope untuk store dengan produk aktif
     */
    public function scopeHasActiveProducts($query)
    {
        return $query->whereHas('products', function($q) {
            $q->where('status', 'active')->where('stock', '>', 0);
        });
    }
}
