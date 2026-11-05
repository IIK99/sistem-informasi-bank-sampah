<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penarikan Saldo Disetujui</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #16a34a, #15803d); padding: 25px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">BANK SAMPAH RW 012</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Pemberitahuan Penarikan Dana Berhasil</p>
        </div>
        <div style="padding: 30px;">
            <p style="margin-top: 0; font-size: 15px;">Halo <strong>{{ $nasabah->name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568;">
                Kabar gembira! Pengajuan penarikan saldo Anda telah <span style="color: #16a34a; font-weight: bold;">DISETUJUI</span> dan diproses oleh petugas kami.
            </p>

            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 18px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #718096; width: 40%;">Kode Penarikan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">{{ $penarikan->kode_penarikan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Nominal Dicairkan</td>
                        <td style="padding: 6px 0; font-weight: 700; color: #16a34a; font-size: 16px;">Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Sisa Saldo Tabungan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">Rp {{ number_format($nasabah->saldo, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Diproses Oleh</td>
                        <td style="padding: 6px 0; color: #2d3748;">{{ $penarikan->petugas->name ?? 'Petugas Bank Sampah' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Waktu Persetujuan</td>
                        <td style="padding: 6px 0; color: #2d3748;">{{ optional($penarikan->tanggal_proses ?? now())->format('d/m/Y H:i') }} WIB</td>
                    </tr>
                    @if($penarikan->catatan_petugas)
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Catatan Petugas</td>
                        <td style="padding: 6px 0; color: #15803d; font-style: italic;">"{{ $penarikan->catatan_petugas }}"</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="font-size: 13px; color: #718096; line-height: 1.5;">
                * Terlampir dokumen <strong>Bukti / Kwitansi Resmi Penarikan Saldo (PDF)</strong> beserta bukti transfer transfer jika dilampirkan.
            </p>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('nasabah.penarikan.index') }}" style="background-color: #16a34a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-block;">
                    Cek Riwayat Penarikan &rarr;
                </a>
            </div>
        </div>
        <div style="background-color: #edf2f7; padding: 16px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            Terima kasih telah aktif menjaga kebersihan lingkungan bersama Bank Sampah RW 012 Curug Kulon.<br>
            &copy; {{ date('Y') }} Bank Sampah RW 012.
        </div>
    </div>
</body>
</html>
