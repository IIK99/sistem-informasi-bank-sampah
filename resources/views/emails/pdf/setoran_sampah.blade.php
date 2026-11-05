<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Setoran Sampah - {{ $setoran->kode_setoran }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 15px; }
        .kop { border-bottom: 2px solid #16a34a; padding-bottom: 10px; margin-bottom: 15px; }
        .kop h2 { margin: 0; color: #15803d; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; color: #718096; font-size: 10px; }
        .title-box { text-align: center; margin: 15px 0; }
        .title-box h3 { margin: 0; font-size: 14px; text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px; }
        .title-box span { font-size: 11px; color: #16a34a; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .info-table td { padding: 3px 0; }
        .table-items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-items th, .table-items td { padding: 7px 8px; border: 1px solid #cbd5e1; font-size: 10px; }
        .table-items th { background-color: #f1f5f9; text-align: center; font-weight: 600; }
        .total-box { background: #f0fdf4; border: 1px solid #86efac; padding: 10px; margin-top: 15px; border-radius: 6px; text-align: right; }
        .total-box h4 { margin: 0; color: #15803d; font-size: 15px; }
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
        <h3>NOTA PENIMBANGAN & SETORAN SAMPAH</h3>
        <span>Nomor Nota: {{ $setoran->kode_setoran }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 18%; color: #64748b;">Nama Nasabah</td>
            <td style="width: 32%;">: <strong>{{ $nasabah->name }}</strong></td>
            <td style="width: 18%; color: #64748b;">Tanggal / Jam</td>
            <td style="width: 32%;">: {{ $setoran->created_at->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="color: #64748b;">Username / ID</td>
            <td>: {{ $nasabah->username }}</td>
            <td style="color: #64748b;">Petugas Kasir</td>
            <td>: {{ $setoran->petugas->name ?? 'Petugas' }}</td>
        </tr>
    </table>

    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="text-align: left;">Jenis Sampah</th>
                <th style="width: 18%;">Timbangan</th>
                <th style="width: 22%; text-align: right;">Harga Satuan</th>
                <th style="width: 25%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @if($setoran->details && $setoran->details->count() > 0)
                @foreach($setoran->details as $idx => $d)
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
                    <td>{{ $setoran->jenisSampah->nama_jenis ?? 'Sampah Daur Ulang' }}</td>
                    <td style="text-align: center;">{{ number_format($setoran->jumlah, 2, ',', '.') }} kg</td>
                    <td style="text-align: right;">Rp {{ number_format($setoran->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($setoran->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="2" style="text-align: right;">TOTAL:</td>
                <td style="text-align: center;">{{ number_format($setoran->jumlah, 2, ',', '.') }} Kg</td>
                <td colspan="2" style="text-align: right; color: #15803d; font-size: 12px;">Rp {{ number_format($setoran->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="total-box">
        <h4>SALDO BERTAMBAH: Rp {{ number_format($setoran->total_harga, 0, ',', '.') }}</h4>
        <span style="font-size: 10px; color: #475569;">Saldo Tabungan Nasabah Terkini: <strong>Rp {{ number_format($nasabah->saldo, 0, ',', '.') }}</strong></span>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Nasabah / Penyetor,<br><br><br><br>
                <strong><u>{{ $nasabah->name }}</u></strong>
            </td>
            <td>
                Petugas Penimbang,<br><br><br><br>
                <strong><u>{{ $setoran->petugas->name ?? 'Petugas Bank Sampah' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
