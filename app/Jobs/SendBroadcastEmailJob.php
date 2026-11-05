<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public string $messageContent
    ) {}

    public function handle(): void
    {
        $nasabahs = User::where('role', 'nasabah')
            ->whereNotNull('email')
            ->get();

        foreach ($nasabahs as $nasabah) {
            if (str_ends_with($nasabah->email, '@banksampah.test')) {
                continue;
            }

            try {
                Mail::send('emails.broadcast', [
                    'nasabah' => $nasabah,
                    'subject' => $this->subject,
                    'pesan'   => $this->messageContent,
                ], function ($mail) use ($nasabah) {
                    $mail->to($nasabah->email, $nasabah->name)
                        ->subject("[Pengumuman Bank Sampah] " . $this->subject);
                });
            } catch (\Throwable $e) {
                Log::error("Gagal kirim broadcast email ke {$nasabah->email}: " . $e->getMessage());
            }
        }
    }

}