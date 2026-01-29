<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',

        // Data Wilayah (Kode & Nama dari API)
        'province_code',
        'province_name',
        'city_code',
        'city_name',
        'district_code',
        'district_name',
        'village_code',
        'village_name',
        'postal_code',

        'detail_address',
        'is_primary',
    ];

    // Casting agar is_primary otomatis jadi true/false (boolean) saat diambil
    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relasi: Alamat milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor Helper: Menampilkan alamat lengkap dalam satu string
     * Cara pakai: $address->full_address
     */
    public function getFullAddressAttribute()
    {
        return "{$this->detail_address}, {$this->village_name}, {$this->district_name}, {$this->city_name}, {$this->province_name} {$this->postal_code}";
    }
}
