<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    /**
     * Display refund management page
     */
    public function index()
    {
        // ✅ Refund yang menunggu buyer isi rekening (seller cancel)
        $needsBankInfoRefunds = Refund::with(['order', 'user'])
            ->where('status', 'needs_bank_info')
            ->latest('requested_at')
            ->paginate(10, ['*'], 'needs_bank_page');

        // Refund siap diproses (rekening sudah diisi)
        $pendingRefunds = Refund::with(['order', 'user'])
            ->where('status', 'pending')
            ->latest('requested_at')
            ->paginate(10, ['*'], 'pending_page');

        // Riwayat
        $processedRefunds = Refund::with(['order', 'user', 'processedBy'])
            ->whereIn('status', ['processed', 'rejected'])
            ->latest('processed_at')
            ->paginate(10, ['*'], 'processed_page');

        return view('admin.refunds.index', compact('needsBankInfoRefunds', 'pendingRefunds', 'processedRefunds'));
    }

    /**
     * Get refund details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $refund = Refund::with(['order', 'user'])->findOrFail($id);

        return response()->json([
            'id'                  => $refund->id,
            'refund_number'       => $refund->refund_number,
            'order_number'        => $refund->order->order_number,
            'user_name'           => $refund->user->name,
            'user_email'          => $refund->user->email,
            'bank_name'           => $refund->bank_name ?? '-',
            'account_number'      => $refund->account_number ?? '-',
            'account_holder'      => $refund->account_holder ?? '-',
            'order_amount'        => number_format($refund->order_amount, 0, ',', '.'),
            'admin_fee'           => number_format($refund->admin_fee, 0, ',', '.'),
            'refund_amount'       => number_format($refund->refund_amount, 0, ',', '.'),
            'cancellation_reason' => $refund->cancellation_reason ?? '-',
            'requested_at'        => $refund->requested_at->format('d F Y H:i'),
            'status'              => $refund->status,
        ]);
    }

    /**
     * Process refund (upload proof & mark as processed)
     */
    public function process(Request $request, $id)
    {
        $refund = Refund::with('order')->findOrFail($id);

        // ✅ Blokir jika buyer belum isi rekening
        if ($refund->status === 'needs_bank_info') {
            return back()->with('error', 'Tidak dapat diproses. Pembeli belum mengisi informasi rekening bank.');
        }

        if ($refund->status !== 'pending') {
            return back()->with('error', 'Refund ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'refund_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'admin_notes'  => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $proofPath = $request->file('refund_proof')->store('refunds', 'public');

            $refund->update([
                'status'       => 'processed',
                'refund_proof' => $proofPath,
                'admin_notes'  => $request->admin_notes,
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $refund->order->update(['refund_status' => 'processed']);

            DB::commit();
            return back()->with('success', 'Refund berhasil diproses! Bukti transfer telah diupload.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($proofPath) && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject refund
     */
    public function reject(Request $request, $id)
    {
        $refund = Refund::with('order')->findOrFail($id);

        // ✅ Bisa tolak dari status needs_bank_info maupun pending
        if (!in_array($refund->status, ['pending', 'needs_bank_info'])) {
            return back()->with('error', 'Refund ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $refund->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at'      => now(),
                'processed_by'     => Auth::id(),
            ]);

            $refund->order->update(['refund_status' => 'rejected']);

            DB::commit();
            return back()->with('success', 'Refund telah ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * View refund proof
     */
    public function viewProof($id)
    {
        $refund = Refund::findOrFail($id);

        if (!$refund->refund_proof || !Storage::disk('public')->exists($refund->refund_proof)) {
            abort(404, 'Bukti refund tidak ditemukan.');
        }

        return Storage::disk('public')->response($refund->refund_proof);
    }
}
