<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengelola Sistem (Murni Akun Login, Tanpa Saldo)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@banksampah.test',
                'no_hp'    => '081234567890',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'name'     => 'Petugas Lapangan',
                'email'    => 'petugas@banksampah.test',
                'no_hp'    => '081234567891',
                'password' => Hash::make('password123'),
                'role'     => 'petugas',
            ]
        );

        // 2. Akun Dummy Nasabah (Khusus Nasabah Diberi Saldo Tabungan Awal Rp 0)
        User::updateOrCreate(
            ['username' => 'nasabah'],
            [
                'name'     => 'Nasabah Warga (Demo)',
                'email'    => 'nasabah@banksampah.test',
                'no_hp'    => null,
                'password' => Hash::make('password123'),
                'role'     => 'nasabah',
                'saldo'    => 0,
            ]
        );

        // 3. Impor Data Warga dari nasabah.csv (Khusus Nasabah Diberi Saldo Awal Rp 0)
        $csvPath = database_path('seeders/nasabah.csv');

        if (!file_exists($csvPath)) {
            $this->command->warn("File nasabah.csv tidak ditemukan di database/seeders!");
            return;
        }

        $fileHandle = fopen($csvPath, 'r');

        // Deteksi pemisah koma atau titik koma bawaan Excel
        $firstLine = fgets($fileHandle);
        $delimiter = str_contains($firstLine, ';') ? ';' : ',';
        rewind($fileHandle);

        // Lewati baris header judul tabel
        fgetcsv($fileHandle, 1000, $delimiter);

        $noUrut = 1;
        while (($row = fgetcsv($fileHandle, 1000, $delimiter)) !== false) {
            $namaLengkap = trim($row[1] ?? '');
            $blokNo      = trim($row[2] ?? '');
            $noHpInput   = trim($row[3] ?? '');
            $emailInput  = trim($row[4] ?? '');

            if (empty($namaLengkap)) {
                continue;
            }

            // Username login murni dari blok rumah (misal: "A1/00" -> "a1-00")
            $cleanBlok = strtolower(str_replace(['/', '\\', ' '], ['-', '-', ''], $blokNo));
            $username  = !empty($cleanBlok) ? $cleanBlok : ('nasabah_' . sprintf('%02d', $noUrut));

            $email = !empty($emailInput) ? $emailInput : ($username . '@banksampah.test');

            $noHp = !empty($noHpInput) ? $noHpInput : null;

            User::updateOrCreate(
                ['username' => $username],
                [
                    'name'     => $namaLengkap,
                    'email'    => $email,
                    'no_hp'    => $noHp, 
                    'password' => Hash::make('password123'),
                    'role'     => 'nasabah',
                    'saldo'    => 0,
                ]
            );

            $noUrut++;
        }

        fclose($fileHandle);
        $this->command->info("Selesai! Berhasil mengimpor " . ($noUrut - 1) . " akun nasabah warga.");
    }
}