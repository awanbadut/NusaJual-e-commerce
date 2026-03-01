<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'weight',
        'unit',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }

    // ✅ NEW: Stock management methods
    public function hasStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    public function reduceStock($quantity)
    {
        if (!$this->hasStock($quantity)) {
            throw new \Exception("Stok {$this->name} tidak mencukupi.");
        }

        $this->decrement('stock', $quantity);
        return $this;
    }

    public function restoreStock($quantity)
    {
        $this->increment('stock', $quantity);
        return $this;
    }
}
