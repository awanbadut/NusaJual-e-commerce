<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WithdrawalController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko belum terdaftar');
        }

        // Get completed orders
        $completedOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->with('items')
            ->get();

        // ✅ HITUNG: SUB_TOTAL + SHIPPING_COST (Mitra dapat ongkir setelah completed)
        $totalSales = 0;
        foreach ($completedOrders as $order) {
            // Jika ada kolom sub_total, pakai itu
            if (Schema::hasColumn('orders', 'sub_total') && $order->sub_total > 0) {
                $subtotal = $order->sub_total;
            } else {
                // Fallback: hitung dari items
                $subtotal = $order->items->sum(function($item) {
                    return $item->quantity * $item->price;
                });
            }
            
            // ✅ TAMBAHKAN ONGKIR (karena mitra sudah bayar ongkir duluan)
            $totalSales += $subtotal + $order->shipping_cost;
        }

        // Total yang sudah ditarik
        $totalWithdrawn = Withdrawal::where('store_id', $store->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        // Total pending withdrawals
        $pendingWithdrawals = Withdrawal::where('store_id', $store->id)
            ->where('status', 'pending')
            ->sum('amount');

        // ✅ PREVENT NEGATIVE BALANCE
        $availableBalance = max(0, $totalSales - $totalWithdrawn);
        $withdrawableBalance = max(0, $availableBalance - $pendingWithdrawals);

        // History withdrawals
        $withdrawals = Withdrawal::where('store_id', $store->id)
            ->with('bankAccount')
            ->latest('requested_at')
            ->paginate(10);

        // Bank accounts
        $bankAccounts = $store->bankAccounts;

        // Admin fee config
        $adminFeeFlat = config('withdrawal.admin_fee_flat', 10000);
        $minAmount = config('withdrawal.minimum_amount', 50000);

        return view('seller.withdrawal.index', compact(
            'store',
            'totalSales',
            'totalWithdrawn',
            'availableBalance',
            'pendingWithdrawals',
            'withdrawableBalance',
            'withdrawals',
            'bankAccounts',
            'adminFeeFlat',
            'minAmount'
        ));
    }

    public function store(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return back()->with('error', 'Toko belum terdaftar');
        }

        $minAmount = config('withdrawal.minimum_amount', 50000);
        
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => "required|numeric|min:{$minAmount}",
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Get completed orders
            $completedOrders = Order::where('store_id', $store->id)
                ->where('status', 'completed')
                ->with('items')
                ->get();

            // ✅ HITUNG: SUB_TOTAL + SHIPPING_COST
            $totalSales = 0;
            foreach ($completedOrders as $order) {
                if (Schema::hasColumn('orders', 'sub_total') && $order->sub_total > 0) {
                    $subtotal = $order->sub_total;
                } else {
                    $subtotal = $order->items->sum(function($item) {
                        return $item->quantity * $item->price;
                    });
                }
                
                $totalSales += $subtotal + $order->shipping_cost;
            }
            
            $totalWithdrawn = Withdrawal::where('store_id', $store->id)
                ->whereIn('status', ['approved', 'completed'])
                ->sum('amount');

            $pendingWithdrawals = Withdrawal::where('store_id', $store->id)
                ->where('status', 'pending')
                ->sum('amount');

            // ✅ PREVENT NEGATIVE
            $availableBalance = max(0, $totalSales - $totalWithdrawn - $pendingWithdrawals);

            // ✅ STRICT VALIDATION
            if ($request->amount > $availableBalance) {
                return back()->with('error', 
                    'Dana tidak mencukupi!' . 
                    '<br>Saldo tersedia: Rp ' . number_format($availableBalance, 0, ',', '.') .
                    '<br>Jumlah diminta: Rp ' . number_format($request->amount, 0, ',', '.')
                );
            }

            // ✅ ADDITIONAL CHECK
            if ($availableBalance <= 0) {
                return back()->with('error', 
                    'Tidak ada saldo yang bisa ditarik. Pastikan ada order yang sudah selesai (completed).'
                );
            }

            // ✅ BIAYA ADMIN FLAT RP 10.000
            $adminFee = Withdrawal::calculateAdminFee($request->amount);
            $totalReceived = Withdrawal::calculateTotalReceived($request->amount, $adminFee);

            // ✅ FINAL CHECK
            if ($totalReceived <= 0) {
                return back()->with('error', 
                    'Jumlah pencairan terlalu kecil. Minimal setelah dipotong biaya admin harus > Rp 0.'
                );
            }

            // Buat withdrawal request
            $withdrawal = Withdrawal::create([
                'store_id' => $store->id,
                'bank_account_id' => $request->bank_account_id,
                'amount' => $request->amount,
                'admin_fee' => $adminFee,
                'total_received' => $totalReceived,
                'notes' => $request->notes,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('seller.withdrawals.index')
                ->with('success', 'Permintaan pencairan dana berhasil dikirim. Dana yang akan diterima: Rp ' . number_format($totalReceived, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan pencairan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $withdrawal = Withdrawal::where('store_id', Auth::user()->store->id)
            ->with(['bankAccount', 'store'])
            ->findOrFail($id);

        return view('seller.withdrawal.show', compact('withdrawal'));
    }

    public function calculateFee(Request $request)
    {
        $amount = $request->amount ?? 0;
        
        if ($amount < config('withdrawal.minimum_amount', 50000)) {
            return response()->json([
                'error' => 'Jumlah minimal withdrawal adalah Rp ' . number_format(config('withdrawal.minimum_amount', 50000), 0, ',', '.')
            ], 400);
        }

        $adminFee = Withdrawal::calculateAdminFee($amount);
        $totalReceived = Withdrawal::calculateTotalReceived($amount, $adminFee);

        return response()->json([
            'amount' => $amount,
            'admin_fee' => $adminFee,
            'total_received' => $totalReceived,
            'formatted' => [
                'amount' => 'Rp ' . number_format($amount, 0, ',', '.'),
                'admin_fee' => 'Rp ' . number_format($adminFee, 0, ',', '.'),
                'total_received' => 'Rp ' . number_format($totalReceived, 0, ',', '.'),
            ]
        ]);
    }
}
