<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faktur Penjualan Sampah ke Pengepul</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #0284c7, #0369a1); padding: 25px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">BANK SAMPAH RW 012</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Faktur Transaksi Penjualan Sampah Daur Ulang</p>
        </div>
        <div style="padding: 30px;">
            <p style="margin-top: 0; font-size: 15px;">Halo <strong>{{ $petugas->name ?? 'Petugas Bank Sampah' }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568;">
                Berikut adalah salinan faktur transaksi penjualan sampah daur ulang ke mitra pengepul <strong>{{ $pengepul->nama_pengepul }}</strong>:
            </p>

            <div style="background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 18px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 40%;">Nomor Faktur</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #0369a1;">{{ $penjualan->kode_penjualan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Tanggal Transaksi</td>
                        <td style="padding: 6px 0; color: #1e293b;">{{ $penjualan->created_at->format('d/m/Y H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Total Timbangan</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ number_format($penjualan->jumlah, 2, ',', '.') }} Kg</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Nilai Transaksi Kotor</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Biaya Operasional</td>
                        <td style="padding: 6px 0; color: #e11d48;">- Rp {{ number_format($penjualan->biaya_operasional ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Kas Bersih Bank Sampah</td>
                        <td style="padding: 6px 0; font-weight: 700; color: #0284c7; font-size: 15px;">Rp {{ number_format($penjualan->total_kas_bersih, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Petugas Kasir</td>
                        <td style="padding: 6px 0; color: #1e293b;">{{ $petugas->name ?? 'Petugas Bank Sampah' }}</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 13px; color: #64748b; line-height: 1.5;">
                * Terlampir berkas <strong>Faktur Penjualan Sampah (PDF)</strong> resmi dengan rincian per kategori sampah.
            </p>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('jual-pengepul.index') }}" style="background-color: #0284c7; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-block;">
                    Buka Riwayat Penjualan &rarr;
                </a>
            </div>
        </div>
        <div style="background-color: #edf2f7; padding: 16px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            Kemitraan Daur Ulang Bank Sampah RW 012 & {{ $pengepul->nama_pengepul }}.<br>
            &copy; {{ date('Y') }} Bank Sampah RW 012.
        </div>
    </div>
</body>
</html>
