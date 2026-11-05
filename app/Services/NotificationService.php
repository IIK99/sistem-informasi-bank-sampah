<?php

namespace App\Services;

use App\Jobs\SendPenarikanEmailJob;
use App\Jobs\SendSetoranEmailJob;
use App\Models\User;
use App\Models\PenarikanSaldo;
use App\Models\SetoranSampah;
use App\Models\PenjualanPengepul;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Helper untuk membuat rekaman notifikasi database (In-App Lonceng)
     */
    protected static function createDbNotification(int $userId, string $type, array $data): void
    {
        try {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => $type,
                'notifiable_type' => User::class,
                'notifiable_id'   => $userId,
                'data'            => json_encode($data),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Gagal membuat DB Notification: " . $e->getMessage());
        }
    }

    /**
     * 1. Pengajuan Penarikan Saldo oleh Nasabah
     * Notifikasi lonceng instan + Email & PDF dikirim lewat Antrean Background
     */
    public static function notifyPengajuanPenarikan(PenarikanSaldo $penarikan): void
    {
        $penarikan->loadMissing(['nasabah']);
        $nasabah = $penarikan->nasabah;

        // Ambil semua petugas & admin
        $staffUsers = User::whereIn('role', ['admin', 'petugas'])->get();

        // 1. Simpan In-App Notification instan ke lonceng staff
        foreach ($staffUsers as $staff) {
            self::createDbNotification($staff->id, 'PengajuanPenarikan', [
                'title'       => 'Pengajuan Penarikan Baru',
                'message'     => "Nasabah {$nasabah->name} mengajukan penarikan Rp " . number_format($penarikan->nominal, 0, ',', '.') . " ({$penarikan->kode_penarikan})",
                'url'         => '/penarikan',
                'icon'        => 'bi-cash-coin',
                'color'       => 'warning',
                'target_menu' => 'penarikan',
                'kode'        => $penarikan->kode_penarikan,
            ]);
        }

        // 2. Lempar pembuatan PDF & kirim email ke antrean database (Async Queue)
        SendPenarikanEmailJob::dispatch($penarikan->id);

        // 3. Kirim notifikasi WhatsApp ke Staf (Admin & Petugas)
        try {
            WhatsAppService::sendPengajuanPenarikanNotification($penarikan);
        } catch (\Throwable $e) {
            Log::error("Gagal kirim notifikasi WhatsApp pengajuan penarikan: " . $e->getMessage());
        }
    }

    /**
     * 2. Penarikan Saldo Disetujui Petugas
     */
    public static function notifyPenarikanDisetujui(PenarikanSaldo $penarikan): void
    {
        $penarikan->loadMissing(['nasabah', 'petugas']);
        $nasabah = $penarikan->nasabah;

        self::createDbNotification($nasabah->id, 'PenarikanDisetujui', [
            'title'       => 'Penarikan Saldo Disetujui',
            'message'     => "Pengajuan {$penarikan->kode_penarikan} sebesar Rp " . number_format($penarikan->nominal, 0, ',', '.') . " berhasil disetujui petugas.",
            'url'         => '/penarikan',
            'icon'        => 'bi-check-circle-fill',
            'color'       => 'success',
            'target_menu' => 'penarikan',
            'kode'        => $penarikan->kode_penarikan,
        ]);

        try {
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
            $pdfContent = $pdf->output();
            $fileName = 'Bukti-Penarikan-' . $penarikan->kode_penarikan . '.pdf';

            if (!empty($nasabah->email) && !str_ends_with($nasabah->email, '@banksampah.test')) {
                Mail::send('emails.penarikan_disetujui', compact('penarikan', 'nasabah'), function ($message) use ($nasabah, $penarikan, $pdfContent, $fileName) {
                    $message->to($nasabah->email, $nasabah->name)
                        ->subject("[Bank Sampah] Penarikan Saldo Disetujui - {$penarikan->kode_penarikan}")
                        ->attachData($pdfContent, $fileName, [
                            'mime' => 'application/pdf',
                        ]);
                });
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email penarikan disetujui: " . $e->getMessage());
        }

        // 3. Kirim notifikasi WhatsApp ke Nasabah
        try {
            WhatsAppService::sendPenarikanDisetujuiNotification($penarikan);
        } catch (\Throwable $e) {
            Log::error("Gagal kirim notifikasi WhatsApp penarikan disetujui: " . $e->getMessage());
        }
    }

    /**
     * 3. Penarikan Saldo Ditolak Petugas
     */
    public static function notifyPenarikanDitolak(PenarikanSaldo $penarikan): void
    {
        $penarikan->loadMissing(['nasabah', 'petugas']);
        $nasabah = $penarikan->nasabah;

        self::createDbNotification($nasabah->id, 'PenarikanDitolak', [
            'title'       => 'Pengajuan Penarikan Ditolak',
            'message'     => "Pengajuan {$penarikan->kode_penarikan} ditolak: " . ($penarikan->catatan_petugas ?: 'Mohon periksa kembali pengajuan Anda.'),
            'url'         => '/penarikan',
            'icon'        => 'bi-x-circle-fill',
            'color'       => 'danger',
            'target_menu' => 'penarikan',
            'kode'        => $penarikan->kode_penarikan,
        ]);

        try {
            $pdf = Pdf::loadView('emails.pdf.penarikan_ditolak', compact('penarikan', 'nasabah'))
                ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $fileName = 'Surat-Penolakan-Penarikan-' . $penarikan->kode_penarikan . '.pdf';

            if (!empty($nasabah->email) && !str_ends_with($nasabah->email, '@banksampah.test')) {
                Mail::send('emails.penarikan_ditolak', compact('penarikan', 'nasabah'), function ($message) use ($nasabah, $penarikan, $pdfContent, $fileName) {
                    $message->to($nasabah->email, $nasabah->name)
                        ->subject("[Bank Sampah] Pemberitahuan Penolakan Penarikan - {$penarikan->kode_penarikan}")
                        ->attachData($pdfContent, $fileName, [
                            'mime' => 'application/pdf',
                        ]);
                });
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email penarikan ditolak: " . $e->getMessage());
        }
    }

    /**
     * 4. Setoran Sampah oleh Petugas
     * Notifikasi lonceng instan + Email & PDF Nota dikirim lewat Antrean Background
     */
    public static function notifySetoranSampah(SetoranSampah $setoran): void
    {
        $setoran->loadMissing(['nasabah', 'petugas', 'details.jenisSampah.satuan', 'details.satuan']);
        $nasabah = $setoran->nasabah;

        // 1. In-App Notification instan untuk Nasabah
        self::createDbNotification($nasabah->id, 'SetoranSampah', [
            'title'       => 'Setoran Sampah Berhasil',
            'message'     => "Setoran nota {$setoran->kode_setoran} senilai Rp " . number_format($setoran->total_harga, 0, ',', '.') . " telah ditambahkan ke saldo tabungan Anda.",
            'url'         => '/riwayat-setoran',
            'icon'        => 'bi-recycle',
            'color'       => 'success',
            'target_menu' => 'setoran',
            'kode'        => $setoran->kode_setoran,
        ]);

        // 2. Lempar pembuatan PDF Nota & kirim email ke antrean database (Async Queue)
        SendSetoranEmailJob::dispatch($setoran->id);

        // 3. Kirim notifikasi WhatsApp ke Nasabah
        try {
            WhatsAppService::sendSetoranNotification($setoran);
        } catch (\Throwable $e) {
            Log::error("Gagal kirim notifikasi WhatsApp setoran sampah: " . $e->getMessage());
        }
    }

    /**
     * 5. Penjualan Sampah ke Pengepul
     */
    public static function notifyPenjualanPengepul(PenjualanPengepul $penjualan): void
    {
        $penjualan->loadMissing(['pengepul', 'petugas', 'details.jenisSampah', 'details.satuan']);
        $pengepul = $penjualan->pengepul;
        $petugas = $penjualan->petugas;

        if ($petugas) {
            self::createDbNotification($petugas->id, 'PenjualanPengepul', [
                'title'       => 'Penjualan Pengepul Berhasil',
                'message'     => "Transaksi nota {$penjualan->kode_penjualan} ke {$pengepul->nama_pengepul} (Kas: Rp " . number_format($penjualan->total_kas_bersih, 0, ',', '.') . ") telah tercatat.",
                'url'         => '/jual-pengepul',
                'icon'        => 'bi-truck',
                'color'       => 'info',
                'target_menu' => 'jual-pengepul',
                'kode'        => $penjualan->kode_penjualan,
            ]);
        }

        try {
            $pdf = Pdf::loadView('emails.pdf.penjualan_pengepul', compact('penjualan', 'pengepul', 'petugas'))
                ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $fileName = 'Faktur-Penjualan-' . $penjualan->kode_penjualan . '.pdf';

            if ($petugas && !empty($petugas->email) && !str_ends_with($petugas->email, '@banksampah.test')) {
                Mail::send('emails.penjualan_pengepul', compact('penjualan', 'pengepul', 'petugas'), function ($message) use ($petugas, $penjualan, $pdfContent, $fileName) {
                    $message->to($petugas->email, $petugas->name)
                        ->subject("[Bank Sampah] Salinan Faktur Penjualan Pengepul - {$penjualan->kode_penjualan}")
                        ->attachData($pdfContent, $fileName, [
                            'mime' => 'application/pdf',
                        ]);
                });
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email penjualan pengepul: " . $e->getMessage());
        }
    }

    /**
     * Tandai notifikasi kategori tertentu sebagai telah dibaca untuk user
     */
    public static function markCategoryAsReadForUser($user, ?string $category): void
    {
        if (!$user || empty($category)) {
            return;
        }

        try {
            $catNormalized = str_replace('_', '-', $category);
            $user->unreadNotifications
                ->filter(function ($n) use ($catNormalized) {
                    $d = is_array($n->data) ? $n->data : json_decode($n->data, true);
                    $target = str_replace('_', '-', $d['target_menu'] ?? '');
                    return $target === $catNormalized;
                })
                ->each(function ($n) {
                    $n->markAsRead();
                });
        } catch (\Throwable $e) {
            Log::error("Gagal markCategoryAsReadForUser: " . $e->getMessage());
        }
    }
}