<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Get withdrawal details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $withdrawal = Withdrawal::with(['store', 'bankAccount'])->findOrFail($id);
        
        return response()->json([
            'store_name' => $withdrawal->store->store_name ?? 'N/A',
            'bank_name' => $withdrawal->bankAccount->bank_name ?? 'N/A',
            'account_number' => $withdrawal->bankAccount->account_number ?? 'N/A',
            'account_holder' => $withdrawal->bankAccount->account_holder ?? 'N/A',
            'amount' => $withdrawal->amount,
            'created_at' => $withdrawal->created_at->format('d F Y'),
        ]);
    }

    /**
     * Approve withdrawal
     */
    public function approve(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        
        $withdrawal->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);
        
        return back()->with('success', 'Penarikan dana berhasil disetujui.');
    }

    /**
     * Process withdrawal with proof upload
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'withdrawal_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'admin_notes' => 'nullable|string',
        ], [
            'withdrawal_proof.required' => 'Bukti pencairan wajib diupload',
            'withdrawal_proof.image' => 'File harus berupa gambar',
            'withdrawal_proof.mimes' => 'Format file harus JPEG, PNG, atau JPG',
            'withdrawal_proof.max' => 'Ukuran file maksimal 2MB',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        // Upload proof
        if ($request->hasFile('withdrawal_proof')) {
            $path = $request->file('withdrawal_proof')->store('withdrawal-proofs', 'public');
            
            $withdrawal->update([
                'withdrawal_proof' => $path,
                'status' => 'completed',
                'processed_at' => now(),
                'processed_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);
        }

        return redirect()->back()->with('success', 'Pencairan dana berhasil diproses! Bukti transfer telah tersimpan.');
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string'
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi'
        ]);
        
        $withdrawal = Withdrawal::findOrFail($id);
        
        $withdrawal->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Penarikan dana ditolak.');
    }

    /**
     * Print withdrawal receipt
     */
    public function print($id)
    {
        $withdrawal = Withdrawal::with(['store', 'bankAccount', 'processedBy'])->findOrFail($id);
        
        if ($withdrawal->status !== 'completed') {
            return back()->with('error', 'Hanya pencairan yang sudah selesai yang bisa dicetak.');
        }
        
        return view('admin.withdrawals.print', compact('withdrawal'));
    }
}
