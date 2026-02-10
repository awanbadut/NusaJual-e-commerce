<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\Withdrawal;

$storeId = 2;

$completedOrders = Order::where('store_id', $storeId)
    ->where('status', 'completed')
    ->get();

$totalSales = $completedOrders->sum('sub_total');

$withdrawals = Withdrawal::where('store_id', $storeId)
    ->whereIn('status', ['approved', 'completed'])
    ->get();

$totalWithdrawn = $withdrawals->sum('amount');
$available = $totalSales - $totalWithdrawn;

echo "\n=== STORE ID: {$storeId} ===\n\n";
echo "Completed Orders: " . $completedOrders->count() . "\n";
echo "Total Sales (sub_total): Rp " . number_format($totalSales, 0, ',', '.') . "\n";
echo "Total Withdrawn: Rp " . number_format($totalWithdrawn, 0, ',', '.') . "\n";
echo "Available Balance: Rp " . number_format($available, 0, ',', '.') . "\n\n";

if ($available < 0) {
    echo "🚨 MASALAH: Withdrawal melebihi sales sebesar Rp " . number_format(abs($available), 0, ',', '.') . "!\n\n";
    
    echo "Detail Completed Orders:\n";
    foreach($completedOrders as $order) {
        echo "  #{$order->order_number}: Rp " . number_format($order->sub_total, 0, ',', '.') . "\n";
    }
    
    echo "\nDetail Withdrawals:\n";
    foreach($withdrawals as $w) {
        echo "  WD-{$w->id}: Rp " . number_format($w->amount, 0, ',', '.') . " ({$w->status}) - {$w->requested_at->format('d M Y H:i')}\n";
    }
    
    echo "\n💡 SOLUSI: Reject withdrawal ID {$withdrawals->first()->id}\n\n";
    echo "Command untuk reject:\n";
    echo "php artisan tinker\n";
    echo "\$w = \\App\\Models\\Withdrawal::find({$withdrawals->first()->id});\n";
    echo "\$w->update(['status' => 'rejected', 'admin_notes' => 'Dibatalkan: saldo tidak mencukupi (fix bug)']);\n";
} else {
    echo "✅ Saldo normal!\n";
}

