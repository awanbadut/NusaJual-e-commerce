<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Store;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal requests (untuk dedicated page)
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['store', 'bankAccount'])
            ->latest('requested_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('requested_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Search by store name or withdrawal ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('store', function($q2) use ($search) {
                      $q2->where('store_name', 'like', "%{$search}%");
                  });
            });
        }

        $withdrawals = $query->paginate(15);

        // Stats
        $stats = [
            'pending_count' => Withdrawal::where('status', 'pending')->count(),
            'approved_count' => Withdrawal::where('status', 'approved')->count(),
            'completed_count' => Withdrawal::where('status', 'completed')->count(),
            'rejected_count' => Withdrawal::where('status', 'rejected')->count(),
            'total_pending_amount' => Withdrawal::where('status', 'pending')->sum('amount'),
            'total_completed_amount' => Withdrawal::where('status', 'completed')->sum('total_received'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * Show withdrawal detail
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['store.user', 'bankAccount'])
            ->findOrFail($id);

        // Get related orders (for verification)
        $relatedOrders = Order::where('store_id', $withdrawal->store_id)
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.withdrawals.show', compact('withdrawal', 'relatedOrders'));
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
            'requested_at' => $withdrawal->requested_at->format('d F Y H:i'),
            'formatted' => [
                'amount' => 'Rp ' . number_format($withdrawal->amount, 0, ',', '.'),
                'admin_fee' => 'Rp ' . number_format($withdrawal->admin_fee, 0, ',', '.'),
                'total_received' => 'Rp ' . number_format($withdrawal->total_received, 0, ',', '.'),
            ]
        ]);
    }

    /**
     * Process withdrawal (Approve & Complete)
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'withdrawal_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'admin_notes' => 'nullable|string|max:500',
        ], [
            'withdrawal_proof.required' => 'Bukti transfer wajib diupload',
            'withdrawal_proof.image' => 'File harus berupa gambar',
            'withdrawal_proof.max' => 'Ukuran file maksimal 2MB',
        ]);

        $withdrawal = Withdrawal::with('store')->findOrFail($id);

        // Check if already processed
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Withdrawal sudah diproses sebelumnya!');
        }

        DB::beginTransaction();
        try {
            // Upload bukti transfer
            $proofPath = $request->file('withdrawal_proof')->store('withdrawals/proofs', 'public');

            // Update withdrawal
            $withdrawal->update([
                'withdrawal_proof' => $proofPath,
                'admin_notes' => $request->admin_notes,
                'status' => 'completed', // Langsung completed
                'processed_at' => now(),
            ]);

            DB::commit();

            // TODO: Send notification to seller (email/WhatsApp)

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 
                    'Pencairan dana berhasil diproses! Dana sebesar Rp ' . 
                    number_format($withdrawal->total_received, 0, ',', '.') . 
                    ' telah ditransfer ke rekening ' . $withdrawal->store->store_name
                );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pencairan: ' . $e->getMessage());
        }
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi',
        ]);

        $withdrawal = Withdrawal::with('store')->findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Withdrawal sudah diproses sebelumnya!');
        }

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'admin_notes' => $request->admin_notes,
                'status' => 'rejected',
                'processed_at' => now(),
            ]);

            DB::commit();

            // TODO: Send notification to seller

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Permintaan pencairan dana dari ' . $withdrawal->store->store_name . ' berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pencairan: ' . $e->getMessage());
        }
    }

    /**
     * Export withdrawals to CSV
     */
    public function export(Request $request)
    {
        $query = Withdrawal::with(['store', 'bankAccount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('requested_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $withdrawals = $query->get();

        $filename = 'withdrawals_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($withdrawals) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'ID',
                'Tanggal Request',
                'Nama Toko',
                'Bank',
                'No Rekening',
                'Nama Pemilik',
                'Jumlah Pencairan',
                'Biaya Admin',
                'Dana Diterima',
                'Status',
                'Tanggal Diproses',
            ]);

            // Data
            foreach ($withdrawals as $w) {
                fputcsv($file, [
                    'WD-' . str_pad($w->id, 4, '0', STR_PAD_LEFT),
                    $w->requested_at->format('d/m/Y H:i'),
                    $w->store->store_name,
                    $w->bankAccount->bank_name,
                    $w->bankAccount->account_number,
                    $w->bankAccount->account_name,
                    $w->amount,
                    $w->admin_fee,
                    $w->total_received,
                    ucfirst($w->status),
                    $w->processed_at ? $w->processed_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
