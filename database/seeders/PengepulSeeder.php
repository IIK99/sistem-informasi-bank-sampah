<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengepul;

class PengepulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengepuls = [
            [
                'kode'          => 'LPK_001',
                'nama_pengepul' => 'Bp. Rafiin',
                'no_telepon'    => '082112996906',
                'alamat'        => 'Cisereh - Curug',
                'status'        => 'aktif',
            ],
            [
                'kode'          => 'LPK_002',
                'nama_pengepul' => 'Bp. H. Sanuri',
                'no_telepon'    => '081310917501',
                'alamat'        => 'Kadu Jaya Bitung - Curug',
                'status'        => 'aktif',
            ],
            [
                'kode'          => 'LPK_003',
                'nama_pengepul' => 'Bp. Edi',
                'no_telepon'    => '089507719744',
                'alamat'        => 'Cukang Galih - Curug',
                'status'        => 'aktif',
            ],
            [
                'kode'          => 'LPK_004',
                'nama_pengepul' => 'Bp. Aep',
                'no_telepon'    => '081213522940',
                'alamat'        => 'Gelam Jaya - Jatiuwung',
                'status'        => 'aktif',
            ],
        ];

        foreach ($pengepuls as $item) {
            Pengepul::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}