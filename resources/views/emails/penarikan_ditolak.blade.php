<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Penarikan Ditolak</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #dc2626, #b91c1c); padding: 25px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">BANK SAMPAH RW 012</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Pemberitahuan Pengajuan Penarikan Ditolak</p>
        </div>
        <div style="padding: 30px;">
            <p style="margin-top: 0; font-size: 15px;">Halo <strong>{{ $nasabah->name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568;">
                Mohon maaf, pengajuan penarikan saldo Anda dengan kode <strong>{{ $penarikan->kode_penarikan }}</strong> belum dapat disetujui oleh petugas.
            </p>

            <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 18px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #718096; width: 40%;">Kode Penarikan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">{{ $penarikan->kode_penarikan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Nominal Diajukan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #2d3748;">Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Alasan Penolakan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #dc2626;">{{ $penarikan->catatan_petugas ?: 'Data tidak sesuai / belum memenuhi kriteria.' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;">Status Saldo</td>
                        <td style="padding: 6px 0; color: #16a34a; font-weight: 600;">Saldo Anda TIDAK dipotong (tetap utuh).</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 13px; color: #718096; line-height: 1.5;">
                * Terlampir berkas <strong>Surat Pemberitahuan Penolakan (PDF)</strong> untuk arsip Anda.
            </p>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('nasabah.penarikan.index') }}" style="background-color: #dc2626; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-block;">
                    Ajukan Ulang Penarikan &rarr;
                </a>
            </div>
        </div>
        <div style="background-color: #edf2f7; padding: 16px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            Jika ada pertanyaan, silakan hubungi pengurus Bank Sampah RW 012.<br>
            &copy; {{ date('Y') }} Bank Sampah RW 012.
        </div>
    </div>
</body>
</html>
