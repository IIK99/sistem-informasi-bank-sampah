<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengepul;
use App\Models\JenisSampah;
use App\Models\HargaPengepul;
use Carbon\Carbon;

class DummyHargaPengepulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengepuls = Pengepul::all();
        $jenisSampahs = JenisSampah::all();

        if ($pengepuls->isEmpty()) {
            $this->command->warn('Data pengepul masih kosong!');
            return;
        }

        if ($jenisSampahs->isEmpty()) {
            $this->command->warn('Data jenis sampah masih kosong!');
            return;
        }

        // Mapping harga sampah otomatis ke seluruh mitra pengepul
        foreach ($pengepuls as $pengepul) {
            foreach ($jenisSampahs as $sampah) {
                // Buat variasi tarif beli pengepul yang realistis dengan margin keuntungan
                $hargaDasar = $sampah->harga_beli ?? 3000;
                $hargaPengepul = $hargaDasar > 0 ? ($hargaDasar + rand(1500, 4000)) : rand(5000, 25000);

                HargaPengepul::updateOrCreate(
                    [
                        'pengepul_id'     => $pengepul->id,
                        'jenis_sampah_id' => $sampah->id,
                    ],
                    [
                        'satuan_id'       => $sampah->satuan_id ?? 1,
                        'harga_beli'      => $hargaPengepul,
                        'tanggal_berlaku' => Carbon::now()->subMonths(1)->toDateString(),
                    ]
                );
            }
        }

        $this->command->info('Katalog harga seluruh mitra pengepul berhasil di-generate!');
    }
}