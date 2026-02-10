<?php

return [
    // ✅ BIAYA ADMIN FLAT
    'admin_fee_flat' => 10000, // Rp 10.000 per withdrawal
    
    // Minimum withdrawal amount
    'minimum_amount' => 50000, // Minimal Rp 50.000
    
    // Maximum withdrawal amount (optional, bisa null untuk unlimited)
    'maximum_amount' => null,
    
    // Auto-approve small amounts (future feature)
    'auto_approve_threshold' => null,
];
