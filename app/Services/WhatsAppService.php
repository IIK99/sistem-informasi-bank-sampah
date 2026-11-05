<?php

namespace App\Services;

use App\Models\PenarikanSaldo;
use App\Models\SetoranSampah;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format nomor HP ke standar internasional Indonesia (628xxx)
     */
    public static function formatPhoneNumber(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Ambil hanya digit angka
        $clean = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali 08xxx -> ubah 0 jadi 62
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }

        return strlen($clean) >= 10 ? $clean : null;
    }

    /**
     * Kirim pesan teks WhatsApp melalui gateway API (misal: Fonnte)
     * Jika token belum disetel di .env, sistem mencatat pesan ke laravel.log (mock)
     */
    public static function sendMessage(?string $phone, string $message): bool
    {
        $target = self::formatPhoneNumber($phone);
        if (!$target) {
            Log::warning("[WhatsApp Service] Nomor HP tidak valid atau kosong: " . ($phone ?? 'null'));
            return false;
        }

        $token = env('FONNTE_TOKEN', env('WA_API_TOKEN', ''));
        $gatewayUrl = env('WA_GATEWAY_URL', 'https://api.fonnte.com/send');

        // 1. Jika token API tersedia, kirim HTTP request ke WhatsApp Gateway
        if (!empty($token)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->timeout(10)->post($gatewayUrl, [
                    'target'      => $target,
                    'message'     => $message,
                    'countryCode' => '62',
                ]);

                if ($response->successful()) {
                    Log::info("[WhatsApp Service] Berhasil mengirim WA ke {$target}");
                    return true;
                } else {
                    Log::error("[WhatsApp Service] Gagal kirim WA ke {$target}: " . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error("[WhatsApp Service] Exception kirim WA ke {$target}: " . $e->getMessage());
            }
        }

        // 2. Logging selalu tercatat di storage/logs/laravel.log untuk monitoring
        Log::info("[WhatsApp Service - MOCK LOG] Terkirim ke {$target}:\n" . $message);
        return true;
    }

    /**
     * 1. Notifikasi Setoran Sampah dari Petugas ke Nasabah
     */
    public static function sendSetoranNotification(SetoranSampah $setoran): void
    {
        $setoran->loadMissing(['nasabah', 'petugas', 'details.jenisSampah']);
        $nasabah = $setoran->nasabah;
        $petugas = $setoran->petugas;

        if (!$nasabah || empty($nasabah->no_hp)) {
            return;
        }

        $itemsSummary = "";
        if ($setoran->details && $setoran->details->isNotEmpty()) {
            foreach ($setoran->details as $d) {
                $namaSampah = $d->jenisSampah->nama_jenis ?? 'Sampah';
                $berat = number_format($d->jumlah, 2, ',', '.');
                $subtotal = number_format($d->subtotal, 0, ',', '.');
                $itemsSummary .= "• {$namaSampah}: {$berat} Kg (Rp {$subtotal})\n";
            }
        }

        $msg = "🌿 *BANK SAMPAH DESA CURUG KULON* 🌿\n";
        $msg .= "*Bukti Penerimaan Setoran Sampah*\n\n";
        $msg .= "Halo *{$nasabah->name}*,\n";
        $msg .= "Setoran sampah Anda telah berhasil ditimbang dan dicatat oleh petugas:\n\n";
        $msg .= "📄 *No. Nota:* {$setoran->kode_setoran}\n";
        $msg .= "📅 *Tanggal:* " . $setoran->created_at->format('d/m/Y H:i') . " WIB\n";
        $msg .= "⚖️ *Total Berat:* " . number_format($setoran->jumlah, 2, ',', '.') . " Kg\n";
        $msg .= "💰 *Total Nilai:* Rp " . number_format($setoran->total_harga, 0, ',', '.') . "\n";
        $msg .= "💳 *Saldo Tabungan Saat Ini:* Rp " . number_format($nasabah->saldo, 0, ',', '.') . "\n";
        $msg .= "👨‍💼 *Petugas Pencatat:* " . ($petugas->name ?? 'Petugas Lapangan') . "\n\n";

        if (!empty($itemsSummary)) {
            $msg .= "*Rincian Sampah Daur Ulang:*\n{$itemsSummary}\n";
        }

        $msg .= "Terima kasih telah berkontribusi memilah sampah demi lingkungan Desa Curug Kulon yang bersih dan lestari! 🌱";

        self::sendMessage($nasabah->no_hp, $msg);
    }

    /**
     * 2. Notifikasi Pengajuan Penarikan Saldo dari Nasabah ke Petugas & Admin
     */
    public static function sendPengajuanPenarikanNotification(PenarikanSaldo $penarikan): void
    {
        $penarikan->loadMissing(['nasabah']);
        $nasabah = $penarikan->nasabah;

        // Ambil semua petugas dan admin yang memiliki nomor HP
        $staffUsers = User::whereIn('role', ['admin', 'petugas'])
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        if ($staffUsers->isEmpty()) {
            return;
        }

        $urlDashboard = url('/penarikan');

        $msg = "🔔 *Pemberitahuan Pengajuan Penarikan Saldo*\n";
        $msg .= "*Bank Sampah Desa Curug Kulon*\n\n";
        $msg .= "Terdapat permohonan penarikan saldo nasabah yang memerlukan verifikasi petugas:\n\n";
        $msg .= "📄 *Kode Penarikan:* {$penarikan->kode_penarikan}\n";
        $msg .= "👤 *Nama Nasabah:* {$nasabah->name} (@{$nasabah->username})\n";
        $msg .= "📱 *Kontak Warga:* " . ($nasabah->no_hp ?: '-') . "\n";
        $msg .= "💵 *Nominal Penarikan:* Rp " . number_format($penarikan->nominal, 0, ',', '.') . "\n";
        $msg .= "📅 *Waktu Pengajuan:* " . $penarikan->created_at->format('d/m/Y H:i') . " WIB\n";
        $msg .= "📝 *Catatan Warga:* " . ($penarikan->catatan_nasabah ?: '-') . "\n\n";
        $msg .= "Silakan login ke portal untuk memproses atau menyetujui penarikan:\n{$urlDashboard}";

        foreach ($staffUsers as $staff) {
            self::sendMessage($staff->no_hp, $msg);
        }
    }

    /**
     * 3. Notifikasi Penarikan Disetujui (ACC) oleh Petugas ke Nasabah
     */
    public static function sendPenarikanDisetujuiNotification(PenarikanSaldo $penarikan): void
    {
        $penarikan->loadMissing(['nasabah', 'petugas']);
        $nasabah = $penarikan->nasabah;
        $petugas = $penarikan->petugas;

        if (!$nasabah || empty($nasabah->no_hp)) {
            return;
        }

        $msg = "✅ *PENARIKAN SALDO DISETUJUI* ✅\n";
        $msg .= "*Bank Sampah Desa Curug Kulon*\n\n";
        $msg .= "Halo *{$nasabah->name}*,\n";
        $msg .= "Permohonan penarikan saldo tabungan sampah Anda telah *DISETUJUI* oleh petugas:\n\n";
        $msg .= "📄 *Kode Penarikan:* {$penarikan->kode_penarikan}\n";
        $msg .= "💵 *Nominal Ditarik:* Rp " . number_format($penarikan->nominal, 0, ',', '.') . "\n";
        $msg .= "💳 *Sisa Saldo Tabungan Anda:* Rp " . number_format($nasabah->saldo, 0, ',', '.') . "\n";
        $msg .= "👨‍💼 *Petugas yang Memproses:* " . ($petugas->name ?? 'Petugas') . "\n";
        $msg .= "📝 *Catatan Petugas:* " . ($penarikan->catatan_petugas ?: 'Disetujui.') . "\n\n";

        if ($penarikan->bukti_transfer) {
            $msg .= "📌 *Bukti transfer telah dilampirkan oleh petugas.* Anda dapat mengunduh bukti kwitansi resmi di akun portal Anda.\n\n";
        } else {
            $msg .= "📌 *Silakan mengambil uang tunai Anda di kantor Bank Sampah Desa Curug Kulon.*\n\n";
        }

        $msg .= "Terima kasih atas partisipasi aktif Anda dalam menjaga lingkungan bersama Bank Sampah Desa Curug Kulon! ✨";

        self::sendMessage($nasabah->no_hp, $msg);
    }
}

