<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Penarikan Saldo</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #16a34a, #15803d); padding: 25px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">BANK SAMPAH RW 012</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Pemberitahuan Pengajuan Penarikan Saldo</p>
        </div>
        <div style="padding: 30px;">
            <p style="margin-top: 0; font-size: 15px;">Halo <strong>{{ $staff->name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568;">
                Ada pengajuan penarikan saldo baru dari nasabah yang memerlukan verifikasi dan persetujuan Anda:
            </p>

            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #718096; width: 40%;">Kode Penarikan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">{{ $penarikan->kode_penarikan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Nama Nasabah</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">{{ $nasabah->name }} (@<span>{{ $nasabah->username }}</span>)</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Nominal Pengajuan</td>
                        <td style="padding: 6px 0; font-weight: 700; color: #16a34a; font-size: 15px;">Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Sisa Saldo Nasabah</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">Rp {{ number_format($nasabah->saldo, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Waktu Pengajuan</td>
                        <td style="padding: 6px 0; color: #2d3748;">{{ $penarikan->created_at->format('d/m/Y H:i') }} WIB</td>
                    </tr>
                    @if($penarikan->catatan_nasabah)
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Catatan Nasabah</td>
                        <td style="padding: 6px 0; color: #4a5568; font-style: italic;">"{{ $penarikan->catatan_nasabah }}"</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="font-size: 13px; color: #718096; line-height: 1.5;">
                * Terlampir berkas <strong>Formulir Pengajuan Penarikan Saldo (PDF)</strong> sebagai lampiran resmi sistem.
            </p>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('nasabah.penarikan.index') }}" style="background-color: #16a34a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-block;">
                    Buka Menu Penarikan Saldo &rarr;
                </a>
            </div>
        </div>
        <div style="background-color: #edf2f7; padding: 16px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} Bank Sampah RW 012 Desa Curug Kulon, Kab. Tangerang.<br>
            Email otomatis, mohon tidak membalas langsung ke alamat ini.
        </div>
    </div>
</body>
</html>
