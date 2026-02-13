<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class AdminBankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['bank_name', 'account_number', 'account_holder', 'is_active'];

    public function getLogoUrlAttribute()
    {
        // Mengubah "CIMB Niaga" menjadi "cimb-niaga"
        $name = Str::slug($this->bank_name);

        // Path file: public/img/banks/nama-bank.svg
        $path = "img/icon/{$name}.svg";

        // Cek jika file ada, jika tidak pakai gambar placeholder/default
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return file_exists(public_path($path)) ? asset($path) : null;
    }
}
