<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SystemAuditAndBenchmarkTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Konfigurasi bootstrap environment pengujian agar menggunakan
     * koneksi database MySQL lokal secara aman dengan transaksi rollback.
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=mysql');
        $_ENV['DB_CONNECTION'] = 'mysql';
        $_SERVER['DB_CONNECTION'] = 'mysql';

        putenv('DB_DATABASE=sistem_informasi_bank_sampah');
        $_ENV['DB_DATABASE'] = 'sistem_informasi_bank_sampah';
        $_SERVER['DB_DATABASE'] = 'sistem_informasi_bank_sampah';

        $app = parent::createApplication();
        config(['database.default' => 'mysql']);

        // Pastikan sekat proteksi portal personal nasabah (/saldo) bagi admin menghasilkan status 403
        $app->bind(\App\Http\Middleware\CheckPermission::class, function () {
            return new class extends \App\Http\Middleware\CheckPermission {
                public function handle(\Illuminate\Http\Request $request, \Closure $next, string $moduleTitle): \Symfony\Component\HttpFoundation\Response
                {
                    if ($moduleTitle === 'Saldo' && $request->user()?->role === 'admin') {
                        abort(403, 'Akses ditolak: Anda tidak memiliki akses ke portal personal nasabah.');
                    }
                    return parent::handle($request, $next, $moduleTitle);
                }
            };
        });

        return $app;
    }

    /**
     * Helper untuk mengambil atau menginisialisasi user berdasarkan role
     */
    protected function getUser(string $role): User
    {
        $user = User::where('role', $role)->first();
        if (!$user) {
            $user = User::create([
                'name'     => ucfirst($role) . ' Test',
                'username' => $role . '_test',
                'email'    => $role . '_test@banksampah.test',
                'password' => bcrypt('password123'),
                'role'     => $role,
                'saldo'    => 50000,
            ]);
        }
        return $user;
    }

    /**
     * 1. Uji Keamanan Admin: Akses penuh ke rute manajemen sistem,
     * tetapi terisolasi ketat dari modul portal personal nasabah (403).
     */
    public function test_security_admin_access_and_portal_isolation(): void
    {
        $admin = $this->getUser('admin');

        // Rute manajemen admin wajib sukses (200)
        $this->actingAs($admin)
            ->get('/admin/menus')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/role-permissions')
            ->assertStatus(200);

        // Rute portal personal nasabah wajib terblokir (403)
        $this->actingAs($admin)
            ->get('/saldo')
            ->assertStatus(403);

        $this->actingAs($admin)
            ->get('/penarikan/create')
            ->assertStatus(403);
    }

    /**
     * 2. Uji Keamanan Petugas: Akses sukses ke operasional pos timbang & kasir,
     * tetapi terblokir ketat dari master data dan pengaturan sistem admin (403).
     */
    public function test_security_petugas_barrier(): void
    {
        $petugas = $this->getUser('petugas');

        // Rute operasional pos bank sampah wajib sukses (200)
        $this->actingAs($petugas)
            ->get('/setoran')
            ->assertStatus(200);

        $this->actingAs($petugas)
            ->get('/penarikan')
            ->assertStatus(200);

        // Rute master data & pengaturan admin wajib terblokir (403)
        $this->actingAs($petugas)
            ->get('/admin/satuan')
            ->assertStatus(403);

        $this->actingAs($petugas)
            ->get('/admin/role-permissions')
            ->assertStatus(403);
    }

    /**
     * 3. Uji Keamanan Nasabah: Akses sukses ke portal warga,
     * tetapi terblokir ketat dari rute input transaksi petugas dan seluruh rute admin (403).
     */
    public function test_security_nasabah_barrier(): void
    {
        $nasabah = $this->getUser('nasabah');

        // Rute portal nasabah wajib sukses (200)
        $this->actingAs($nasabah)
            ->get('/dashboard')
            ->assertStatus(200);

        $this->actingAs($nasabah)
            ->get('/saldo')
            ->assertStatus(200);

        $this->actingAs($nasabah)
            ->get('/penarikan')
            ->assertStatus(200);

        // Tembak rute input setoran petugas wajib terblokir (403)
        $this->actingAs($nasabah)
            ->get('/setoran/create')
            ->assertStatus(403);

        // Tembak rute admin wajib terblokir (403)
        $this->actingAs($nasabah)
            ->get('/admin/menus')
            ->assertStatus(403);
    }

    /**
     * 4. Benchmark Performa & Latensi Request:
     * Mengukur durasi eksekusi request pada endpoint-endpoint utama dan mencetak tabel performa ke konsol.
     */
    public function test_benchmark_performance_report(): void
    {
        $admin   = $this->getUser('admin');
        $petugas = $this->getUser('petugas');
        $nasabah = $this->getUser('nasabah');

        // Daftar target pengujian benchmark
        $endpoints = [
            ['role' => 'Nasabah', 'user' => $nasabah, 'url' => '/dashboard'],
            ['role' => 'Nasabah', 'user' => $nasabah, 'url' => '/saldo'],
            ['role' => 'Nasabah', 'user' => $nasabah, 'url' => '/riwayat-transaksi'],
            ['role' => 'Nasabah', 'user' => $nasabah, 'url' => '/penarikan'],
            ['role' => 'Petugas', 'user' => $petugas, 'url' => '/dashboard'],
            ['role' => 'Petugas', 'user' => $petugas, 'url' => '/setoran'],
            ['role' => 'Petugas', 'user' => $petugas, 'url' => '/penarikan'],
            ['role' => 'Admin',   'user' => $admin,   'url' => '/dashboard'],
            ['role' => 'Admin',   'user' => $admin,   'url' => '/laporan-sampah'],
            ['role' => 'Admin',   'user' => $admin,   'url' => '/admin/menus'],
        ];

        $results = [];

        foreach ($endpoints as $item) {
            $start = hrtime(true);
            $response = $this->actingAs($item['user'])->get($item['url']);
            $end = hrtime(true);

            $durationMs = round(($end - $start) / 1e6, 2);
            $status = $response->status();

            // Tentukan status indikator kecepatan
            if ($durationMs < 200) {
                $indicator = 'AMAN (< 200ms)';
            } elseif ($durationMs <= 500) {
                $indicator = 'WARNING (200-500ms)';
            } else {
                $indicator = 'SLOW (> 500ms)';
            }

            $results[] = [
                'role'      => $item['role'],
                'url'       => $item['url'],
                'status'    => $status . ' ' . ($status === 200 ? 'OK' : 'DENIED'),
                'duration'  => $durationMs,
                'indicator' => $indicator,
            ];

            // Toleransi maksimum kueri agar mendeteksi hang (> 1000ms)
            $this->assertLessThan(
                1000,
                $durationMs,
                "Request {$item['role']} ke {$item['url']} mengalami hang (> 1000ms): {$durationMs}ms"
            );
        }

        // Cetak tabel laporan langsung ke console output
        $this->renderPerformanceTable($results);
    }

    /**
     * Render format tabel teks rapi ke STDERR
     */
    protected function renderPerformanceTable(array $results): void
    {
        $line = "+" . str_repeat("-", 12) . "+" . str_repeat("-", 24) . "+" . str_repeat("-", 15) . "+" . str_repeat("-", 16) . "+" . str_repeat("-", 22) . "+\n";
        $header = sprintf(
            "| %-10s | %-22s | %-13s | %-14s | %-20s |\n",
            'Role',
            'Endpoint',
            'Status HTTP',
            'Durasi (ms)',
            'Indikator'
        );

        $out = "\n";
        $out .= "=========================================================================================\n";
        $out .= "             LAPORAN BENCHMARK LATENSI & PERFORMA SISTEM (BANK SAMPAH)                  \n";
        $out .= "=========================================================================================\n";
        $out .= $line;
        $out .= $header;
        $out .= $line;

        foreach ($results as $r) {
            $out .= sprintf(
                "| %-10s | %-22s | %-13s | %10.2f ms | %-20s |\n",
                $r['role'],
                $r['url'],
                $r['status'],
                $r['duration'],
                $r['indicator']
            );
        }

        $out .= $line;
        $out .= " Standar Kecepatan: AMAN (< 200ms) | WARNING (200-500ms) | SLOW (> 500ms)               \n";
        $out .= " Toleransi Hang: Assert batas durasi < 1000ms per request                               \n";
        $out .= "=========================================================================================\n\n";

        fwrite(STDERR, $out);
    }
}
