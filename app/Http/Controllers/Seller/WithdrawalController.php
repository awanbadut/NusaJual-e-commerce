<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko belum terdaftar');
        }

        // Total penjualan yang sudah completed
        $totalSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Total yang sudah ditarik (approved + completed)
        $totalWithdrawn = Withdrawal::where('store_id', $store->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        // Dana tersedia
        $availableBalance = $totalSales - $totalWithdrawn;

        // Total pending withdrawals
        $pendingWithdrawals = Withdrawal::where('store_id', $store->id)
            ->where('status', 'pending')
            ->sum('amount');

        // History withdrawals
        $withdrawals = Withdrawal::where('store_id', $store->id)
            ->with('bankAccount')
            ->latest()
            ->paginate(10);

        // Bank accounts
        $bankAccounts = $store->bankAccounts;

        // Admin fee config
        $adminFeeFixed = config('withdrawal.admin_fee_fixed', 5000);
        $adminFeePercentage = config('withdrawal.admin_fee_percentage', 1);
        $minAmount = config('withdrawal.minimum_amount', 50000);

        return view('seller.withdrawal.index', compact(
            'store',
            'totalSales',
            'totalWithdrawn',
            'availableBalance',
            'pendingWithdrawals',
            'withdrawals',
            'bankAccounts',
            'adminFeeFixed',
            'adminFeePercentage',
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

        // Cek dana tersedia
        $totalSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalWithdrawn = Withdrawal::where('store_id', $store->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('store_id', $store->id)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = $totalSales - $totalWithdrawn - $pendingWithdrawals;

        if ($request->amount > $availableBalance) {
            return back()->with('error', 'Dana tidak mencukupi. Saldo tersedia: Rp ' . number_format($availableBalance, 0, ',', '.'));
        }

        // Calculate admin fee
        $adminFee = Withdrawal::calculateAdminFee($request->amount);
        $totalReceived = Withdrawal::calculateTotalReceived($request->amount, $adminFee);

        // Buat withdrawal request
        Withdrawal::create([
            'store_id' => $store->id,
            'bank_account_id' => $request->bank_account_id,
            'amount' => $request->amount,
            'admin_fee' => $adminFee,
            'total_received' => $totalReceived,
            'notes' => $request->notes,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->route('seller.withdrawals.index')
            ->with('success', 'Permintaan pencairan dana berhasil dikirim. Dana yang akan diterima: Rp ' . number_format($totalReceived, 0, ',', '.'));
    }

    public function show($id)
    {
        $withdrawal = Withdrawal::where('store_id', Auth::user()->store->id)
            ->with(['bankAccount', 'store'])
            ->findOrFail($id);

        return view('seller.withdrawal.show', compact('withdrawal'));
    }

    /**
     * AJAX: Calculate admin fee preview
     */
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
