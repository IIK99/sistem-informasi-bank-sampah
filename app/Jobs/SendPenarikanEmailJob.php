<?php

namespace App\Jobs;

use App\Models\PenarikanSaldo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPenarikanEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $penarikanId) {}

    public function handle(): void
    {
        $penarikan = PenarikanSaldo::with('nasabah')->find($this->penarikanId);
        if (!$penarikan) return;

        $nasabah = $penarikan->nasabah;
        $staffUsers = User::whereIn('role', ['admin', 'petugas'])->get();

        try {
            // Render formulir PDF di latar belakang
            $pdf = Pdf::loadView('emails.pdf.pengajuan_penarikan', compact('penarikan', 'nasabah'))
                ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $fileName = 'Formulir-Pengajuan-Penarikan-' . $penarikan->kode_penarikan . '.pdf';

            // Kirim ke email staf yang valid (abaikan email dummy)
            foreach ($staffUsers as $staff) {
                if (empty($staff->email) || str_ends_with($staff->email, '@banksampah.test')) {
                    continue;
                }

                Mail::send('emails.pengajuan_penarikan', compact('penarikan', 'nasabah', 'staff'), function ($message) use ($staff, $penarikan, $pdfContent, $fileName) {
                    $message->to($staff->email, $staff->name)
                        ->subject("[Bank Sampah] Pengajuan Penarikan Baru - {$penarikan->kode_penarikan}")
                        ->attachData($pdfContent, $fileName, [
                            'mime' => 'application/pdf',
                        ]);
                });
            }
        } catch (\Throwable $e) {
            Log::error("Queue SendPenarikanEmailJob Gagal: " . $e->getMessage());
        }
    }
}