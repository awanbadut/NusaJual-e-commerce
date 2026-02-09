<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal requests (untuk dedicated page)
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['store', 'bankAccount'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by store name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('store', function($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%");
            });
        }

        $withdrawals = $query->paginate(15);

        // Stats
        $stats = [
            'pending' => Withdrawal::where('status', 'pending')->count(),
            'approved' => Withdrawal::where('status', 'approved')->count(),
            'completed' => Withdrawal::where('status', 'completed')->count(),
            'rejected' => Withdrawal::where('status', 'rejected')->count(),
            'total_pending_amount' => Withdrawal::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * Get withdrawal details (AJAX for modal)
     */
    public function getDetails($id)
    {
        $withdrawal = Withdrawal::with(['store', 'bankAccount'])
            ->findOrFail($id);

        return response()->json([
            'id' => $withdrawal->id,
            'store_name' => $withdrawal->store->store_name,
            'bank_name' => $withdrawal->bankAccount->bank_name,
            'account_number' => $withdrawal->bankAccount->account_number,
            'account_holder' => $withdrawal->bankAccount->account_name,
            'amount' => $withdrawal->amount,
            'admin_fee' => $withdrawal->admin_fee,
            'total_received' => $withdrawal->total_received,
            'notes' => $withdrawal->notes,
            'requested_at' => $withdrawal->requested_at->format('d F Y'),
        ]);
    }

    /**
     * Process withdrawal (Approve & Complete)
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'withdrawal_proof' => 'required|image|max:2048',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $withdrawal = Withdrawal::with('store')->findOrFail($id);

        // Check if already processed
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Withdrawal sudah diproses sebelumnya!');
        }

        // Upload bukti transfer
        $proofPath = $request->file('withdrawal_proof')->store('withdrawals/proofs', 'public');

        // Update withdrawal
        $withdrawal->update([
            'withdrawal_proof' => $proofPath,
            'admin_notes' => $request->admin_notes,
            'status' => 'completed', // Langsung completed (atau bisa 'approved' dulu)
            'processed_at' => now(),
        ]);

        // TODO: Send notification to seller (email/WhatsApp)

        return redirect()->back()->with('success', 
            'Pencairan dana berhasil diproses! Dana sebesar Rp ' . number_format($withdrawal->total_received, 0, ',', '.') . ' telah ditransfer ke rekening mitra.'
        );
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Withdrawal sudah diproses sebelumnya!');
        }

        $withdrawal->update([
            'admin_notes' => $request->admin_notes,
            'status' => 'rejected',
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan pencairan dana berhasil ditolak.');
    }
}
