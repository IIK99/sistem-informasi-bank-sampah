<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faktur Penjualan Pengepul - {{ $penjualan->kode_penjualan }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 15px; }
        .kop { border-bottom: 2px solid #0284c7; padding-bottom: 10px; margin-bottom: 15px; }
        .kop h2 { margin: 0; color: #0369a1; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; color: #718096; font-size: 10px; }
        .title-box { text-align: center; margin: 15px 0; }
        .title-box h3 { margin: 0; font-size: 14px; text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px; }
        .title-box span { font-size: 11px; color: #0284c7; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .info-table td { padding: 3px 0; }
        .table-items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-items th, .table-items td { padding: 7px 8px; border: 1px solid #cbd5e1; font-size: 10px; }
        .table-items th { background-color: #f1f5f9; text-align: center; font-weight: 600; }
        .total-box { background: #f0f9ff; border: 1px solid #bae6fd; padding: 10px; margin-top: 15px; border-radius: 6px; }
        .total-box table { width: 100%; font-size: 11px; }
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .signature-table td { width: 50%; text-align: center; font-size: 10px; border: none; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>BANK SAMPAH RW 012</h2>
        <p>Desa Curug Kulon, Kecamatan Curug, Kabupaten Tangerang, Banten</p>
        <p>Telepon / WA: 0812-3456-7890 | Email: banksampahcurug@gmail.com</p>
    </div>

    <div class="title-box">
        <h3>FAKTUR PENJUALAN SAMPAH KE PENGEPUL</h3>
        <span>Nomor Faktur: {{ $penjualan->kode_penjualan }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 20%; color: #64748b;">Mitra Pengepul</td>
            <td style="width: 30%;">: <strong>{{ $pengepul->nama_pengepul }}</strong></td>
            <td style="width: 20%; color: #64748b;">Tanggal Faktur</td>
            <td style="width: 30%;">: {{ $penjualan->created_at->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="color: #64748b;">Kontak Pengepul</td>
            <td>: {{ $pengepul->no_telepon ?: '-' }}</td>
            <td style="color: #64748b;">Petugas Kasir</td>
            <td>: {{ $petugas->name ?? 'Petugas' }}</td>
        </tr>
        <tr>
            <td style="color: #64748b;">Alamat Lapak</td>
            <td colspan="3">: {{ $pengepul->alamat ?: '-' }}</td>
        </tr>
    </table>

    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="text-align: left;">Jenis Sampah</th>
                <th style="width: 18%;">Timbangan</th>
                <th style="width: 22%; text-align: right;">Harga Beli Pengepul</th>
                <th style="width: 25%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @if($penjualan->details && $penjualan->details->count() > 0)
                @foreach($penjualan->details as $idx => $d)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>{{ $d->jenisSampah->nama_jenis ?? '-' }}</td>
                        <td style="text-align: center;">{{ number_format($d->jumlah, 2, ',', '.') }} {{ $d->satuan->simbol ?? 'kg' }}</td>
                        <td style="text-align: right;">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 600;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $penjualan->jenisSampah->nama_jenis ?? 'Sampah Daur Ulang' }}</td>
                    <td style="text-align: center;">{{ number_format($penjualan->jumlah, 2, ',', '.') }} kg</td>
                    <td style="text-align: right;">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="2" style="text-align: right;">TOTAL:</td>
                <td style="text-align: center;">{{ number_format($penjualan->jumlah, 2, ',', '.') }} Kg</td>
                <td colspan="2" style="text-align: right; color: #0369a1; font-size: 12px;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td style="color: #64748b;">Total Penjualan Kotor:</td>
                <td style="text-align: right; font-weight: 600;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @if($penjualan->biaya_operasional > 0)
            <tr>
                <td style="color: #e11d48;">Biaya Operasional ({{ $penjualan->keterangan_operasional ?: 'Operasional angkut' }}):</td>
                <td style="text-align: right; color: #e11d48; font-weight: 600;">- Rp {{ number_format($penjualan->biaya_operasional, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding-top: 5px; font-weight: bold; font-size: 13px; color: #0369a1;">KAS BERSIH BANK SAMPAH:</td>
                <td style="padding-top: 5px; text-align: right; font-weight: bold; font-size: 13px; color: #0369a1;">Rp {{ number_format($penjualan->total_kas_bersih, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Pihak Pembeli (Pengepul),<br><br><br><br>
                <strong><u>{{ $pengepul->nama_pengepul }}</u></strong>
            </td>
            <td>
                Petugas Penjualan,<br><br><br><br>
                <strong><u>{{ $petugas->name ?? 'Pengurus Bank Sampah' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
