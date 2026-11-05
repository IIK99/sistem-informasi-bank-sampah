<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSampah;
use App\Models\Satuan;
use App\Models\JenisSampah;

class DataSampahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Accu' => [
                ['kode' => 'BS-001', 'nama' => 'Aki (Kg)', 'satuan' => 'Kg'],
            ],
            'Beling/Kaca' => [
                ['kode' => 'BS-002', 'nama' => 'Beling/Botol (Kg)', 'satuan' => 'Kg'],
            ],
            'Elektronik' => [
                ['kode' => 'BS-003', 'nama' => 'Cooler (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-004', 'nama' => 'Kipas Angin (Pcs)', 'satuan' => 'Pcs'],
                ['kode' => 'BS-005', 'nama' => 'Mesin Air Satelit (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-006', 'nama' => 'Mesin Cuci (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-007', 'nama' => 'Mesin Cuci (Pcs)', 'satuan' => 'Pcs'],
                ['kode' => 'BS-008', 'nama' => 'Rice Cooker (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-009', 'nama' => 'Setrikaan (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-010', 'nama' => 'TV 43In (Kg)', 'satuan' => 'Kg'],
            ],
            'Gabrukan' => [
                ['kode' => 'BS-011', 'nama' => 'Emberan (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-012', 'nama' => 'Gabrukan (Kg)', 'satuan' => 'Kg'],
            ],
            'Galon' => [
                ['kode' => 'BS-013', 'nama' => 'Galon Aqua (Pcs)', 'satuan' => 'Pcs'],
                ['kode' => 'BS-014', 'nama' => 'Galon Leminerale (Pcs)', 'satuan' => 'Pcs'],
                ['kode' => 'BS-015', 'nama' => 'Galon Lainnya (Pcs)', 'satuan' => 'Pcs'],
            ],
            'Logam' => [
                ['kode' => 'BS-016', 'nama' => 'Alumunium (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-017', 'nama' => 'Baja Ringan (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-018', 'nama' => 'Besi (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-019', 'nama' => 'Kaleng (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-020', 'nama' => 'Kalvanis (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-021', 'nama' => 'Kuningan (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-022', 'nama' => 'Seng (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-023', 'nama' => 'Sepeda (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-024', 'nama' => 'Stainless Stell (Kg) A', 'satuan' => 'Kg'],
                ['kode' => 'BS-025', 'nama' => 'Stainless Stell (Kg) B', 'satuan' => 'Kg'],
                ['kode' => 'BS-026', 'nama' => 'Tembaga (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-027', 'nama' => 'Wajan Alumunium (Kg)', 'satuan' => 'Kg'],
            ],
            'Naso' => [
                ['kode' => 'BS-028', 'nama' => 'Naso (Kg)', 'satuan' => 'Kg'],
            ],
            'Paper' => [
                ['kode' => 'BS-029', 'nama' => 'Buku (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-030', 'nama' => 'Duplek/Boncos (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-031', 'nama' => 'HVS (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-032', 'nama' => 'Kantong Semen (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-033', 'nama' => 'Kardus (Kg)', 'satuan' => 'Kg'],
            ],
            'Plastik' => [
                ['kode' => 'BS-034', 'nama' => 'Botol Plastik Bersih (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-035', 'nama' => 'Botol Plastik Kotor (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-036', 'nama' => 'Gelas Plastik Kotor (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-037', 'nama' => 'Gelas Plastik Bersih (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-038', 'nama' => 'Plastik Asoy (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-039', 'nama' => 'Tutup Botol Plastik (Kg)', 'satuan' => 'Kg'],
                ['kode' => 'BS-040', 'nama' => 'Tutup Galon (Kg)', 'satuan' => 'Kg'],
            ],
            'PVC' => [
                ['kode' => 'BS-041', 'nama' => 'PVC (Kg)', 'satuan' => 'Kg'],
            ],
            'Pallet' => [
                ['kode' => 'PRO-01', 'nama' => 'Pallet Kayu', 'satuan' => 'Pcs'],
            ],
            'Drum' => [
                ['kode' => 'PRO-02', 'nama' => 'Drum', 'satuan' => 'Kg'],
            ],
            'Colorbound' => [
                ['kode' => 'PRO-03', 'nama' => 'Colorbound', 'satuan' => 'Kg'],
            ],
        ];

        foreach ($data as $kategoriNama => $items) {
            $kategori = KategoriSampah::firstOrCreate(['nama_kategori' => $kategoriNama]);
            
            foreach ($items as $item) {
                // Tentukan simbol dan nama
                $namaSatuan = $item['satuan'] === 'Kg' ? 'Kilogram' : 'Pieces';
                $simbol = $item['satuan'];

                $satuan = Satuan::firstOrCreate(
                    ['simbol' => $simbol],
                    ['nama_satuan' => $namaSatuan]
                );
                
                JenisSampah::updateOrCreate(
                    ['kode_jenis' => $item['kode']],
                    [
                        'kategori_id' => $kategori->id,
                        'satuan_id' => $satuan->id,
                        'nama_jenis' => $item['nama'],
                        'status' => 'aktif'
                    ]
                );
            }
        }
    }
}
