<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Withdrawal Fee Configuration
    |--------------------------------------------------------------------------
    |
    | Configure withdrawal fee settings
    |
    */

    // Biaya admin tetap (fixed fee)
    'admin_fee_fixed' => 5000, // Rp 5.000

    // Biaya admin persentase (percentage)
    'admin_fee_percentage' => 1, // 1%

    // Minimal withdrawal amount
    'minimum_amount' => 50000, // Rp 50.000

    // Maksimal withdrawal per request
    'maximum_amount' => 50000000, // Rp 50 juta
];
