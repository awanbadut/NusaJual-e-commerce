<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminBankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['bank_name', 'account_number', 'account_holder', 'is_active'];
}
