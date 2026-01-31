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
        'description',
        'logo', // Pastikan di database namanya 'logo' sesuai migrasi awal kamu

        // --- TAMBAHAN BARU (Sesuai Controller) ---
        'owner_name',
        'phone',

        // --- WILAYAH ---
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

        // --- TAMBAHAN BANK (Sesuai Controller) ---
        'bank_name',
        'account_number',
        'account_holder',
    ];

    // ... Relasi tetap sama ...
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
}
