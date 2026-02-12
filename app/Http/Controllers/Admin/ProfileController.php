<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        $bankAccounts = AdminBankAccount::orderBy('is_active', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.profile.index', compact('admin', 'bankAccounts'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    // UPDATE PADA STORE BANK
    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|in:BCA,BNI,BRI,Mandiri,BSI,CIMB Niaga,Danamon,Permata,Maybank,BTN',
            'account_number' => 'required|numeric',
            'account_holder' => 'required|string',
        ]);

        // 1. Non-aktifkan SEMUA rekening yang ada dulu
        AdminBankAccount::query()->update(['is_active' => true]);

        // 2. Buat rekening baru dengan status is_active = true
        AdminBankAccount::create(array_merge($request->all(), ['is_active' => false]));

        return back()->with('success', 'Rekening berhasil ditambahkan dan diaktifkan!');
    }

    // FUNGSI TOGGLE STATUS (Aktif/Non-Aktif)
    public function toggleBank($id)
    {
        $bank = AdminBankAccount::findOrFail($id);

        if ($bank->is_active) {
            // Kalau sedang aktif, dimatikan
            $bank->update(['is_active' => false]);
            $message = 'Rekening berhasil dinonaktifkan.';
        } else {
            // Kalau sedang mati, nyalakan (dan matikan yang lain)
            AdminBankAccount::where('id', '!=', $id)->update(['is_active' => false]);
            $bank->update(['is_active' => true]);
            $message = 'Rekening diaktifkan (Rekening lain otomatis non-aktif).';
        }

        return back()->with('success', $message);
    }

    // FUNGSI DELETE: Hapus Permanen
    public function destroyBank($id)
    {
        $bank = AdminBankAccount::findOrFail($id);
        $bank->delete();

        return back()->with('success', 'Rekening berhasil dihapus permanen!');
    }
}
