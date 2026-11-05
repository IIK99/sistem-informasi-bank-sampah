<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PenarikanSaldo;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PenarikanController extends Controller
{
    // 1. Tampilkan daftar pengajuan penarikan dana seluruh warga
    public function index()
    {
        NotificationService::markCategoryAsReadForUser(Auth::user(), 'penarikan');

        $penarikans = PenarikanSaldo::with(['nasabah', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('petugas.penarikan.index', compact('penarikans'));
    }

    // 2. Petugas menyetujui (ACC) dengan lampiran bukti transfer & potong saldo nasabah
    public function setujui(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer'  => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'catatan_petugas' => 'nullable|string|max:500',
        ], [
            'bukti_transfer.file'     => 'File bukti transfer tidak valid.',
            'bukti_transfer.mimes'    => 'Bukti transfer harus berupa file gambar (JPG, JPEG, PNG) atau dokumen PDF.',
            'bukti_transfer.max'      => 'Ukuran file bukti transfer maksimal 2MB.',
        ]);

        $penarikan = PenarikanSaldo::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah pernah diproses sebelumnya.');
        }

        $nasabah = $penarikan->nasabah;

        // Validasi: Pastikan saldo nasabah masih cukup
        if ($nasabah->saldo < $penarikan->nominal) {
            return back()->with('error', 'Gagal memproses! Saldo nasabah saat ini tidak mencukupi nominal penarikan.');
        }

        // Upload berkas bukti transfer jika dilampirkan
        $path = $penarikan->bukti_transfer;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = 'bukti_' . $penarikan->kode_penarikan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_transfer', $fileName, 'public');
        }

        // 1. Otomatis potong saldo nasabah
        $nasabah->decrement('saldo', $penarikan->nominal);

        // 2. Ubah status pengajuan jadi disetujui & simpan path bukti transfer
        $defaultCatatan = $path ? 'Disetujui. Bukti transfer telah dilampirkan.' : 'Disetujui oleh petugas.';
        $penarikan->update([
            'status'          => 'disetujui',
            'petugas_id'      => Auth::id(),
            'tanggal_proses'  => now(),
            'catatan_petugas' => $request->catatan_petugas ?: $defaultCatatan,
            'bukti_transfer'  => $path,
        ]);

        $pesan = "Penarikan Rp " . number_format($penarikan->nominal, 0, ',', '.') . " untuk {$nasabah->name} ({$penarikan->kode_penarikan}) berhasil disetujui.";
        if ($path) {
            $pesan .= " Bukti transfer telah dilampirkan.";
        }

        try {
            \App\Services\NotificationService::notifyPenarikanDisetujui($penarikan);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Notifikasi penarikan disetujui error: ' . $e->getMessage());
        }

        return back()->with('success', $pesan);
    }

    // 3. Petugas menolak pengajuan penarikan
    public function tolak(Request $request, $id)
    {
        $penarikan = PenarikanSaldo::with(['nasabah', 'petugas'])->findOrFail($id);

        if ($penarikan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah pernah diproses.');
        }

        $penarikan->update([
            'status'          => 'ditolak',
            'petugas_id'      => Auth::id(),
            'tanggal_proses'  => now(),
            'catatan_petugas' => $request->catatan_petugas ?: 'Pengajuan penarikan ditolak oleh petugas.',
        ]);

        try {
            \App\Services\NotificationService::notifyPenarikanDitolak($penarikan);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Notifikasi penarikan ditolak error: ' . $e->getMessage());
        }

        return back()->with('success', 'Pengajuan penarikan berhasil ditolak.');
    }

    // 4. Cetak Kwitansi / Slip Bukti Penarikan ke PDF (dengan Bukti Transfer)
    public function cetakKwitansiPdf($id)
    {
        $penarikan = PenarikanSaldo::with(['nasabah', 'petugas'])->findOrFail($id);

        // Nasabah hanya dapat mencetak bukti penarikan miliknya sendiri
        if (Auth::user()->role === 'nasabah' && $penarikan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencetak transaksi ini.');
        }

        $buktiBase64 = null;
        $isImage = false;

        if ($penarikan->bukti_transfer) {
            $filePath = storage_path('app/public/' . $penarikan->bukti_transfer);
            if (file_exists($filePath)) {
                $mime = mime_content_type($filePath);
                if (str_starts_with($mime, 'image/')) {
                    $isImage = true;
                    $buktiBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $pdf = Pdf::loadView('petugas.penarikan.pdf_kwitansi', compact('penarikan', 'buktiBase64', 'isImage'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Bukti-Penarikan-' . $penarikan->kode_penarikan . '.pdf');
    }
}