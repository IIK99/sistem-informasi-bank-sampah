<?php

namespace App\Jobs;

use App\Models\SetoranSampah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSetoranEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $setoranId) {}

    public function handle(): void
    {
        $setoran = SetoranSampah::with(['nasabah', 'petugas', 'details.jenisSampah.satuan', 'details.satuan'])->find($this->setoranId);
        if (!$setoran) return;

        $nasabah = $setoran->nasabah;
        
        // Pengaman agar tidak mengirim ke email dummy bawaan seeder
        if (empty($nasabah->email) || str_ends_with($nasabah->email, '@banksampah.test')) {
            return;
        }

        try {
            // Render nota PDF di latar belakang
            $pdf = Pdf::loadView('emails.pdf.setoran_sampah', compact('setoran', 'nasabah'))
                ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $fileName = 'Nota-Setoran-' . $setoran->kode_setoran . '.pdf';

            // Kirim email nota transaksi ke nasabah
            Mail::send('emails.setoran_sampah', compact('setoran', 'nasabah'), function ($message) use ($nasabah, $setoran, $pdfContent, $fileName) {
                $message->to($nasabah->email, $nasabah->name)
                    ->subject("[Bank Sampah] Bukti Setoran Sampah - {$setoran->kode_setoran}")
                    ->attachData($pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
        } catch (\Throwable $e) {
            Log::error("Queue SendSetoranEmailJob Gagal: " . $e->getMessage());
        }
    }
}