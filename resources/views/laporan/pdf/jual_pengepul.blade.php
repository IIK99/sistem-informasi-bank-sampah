<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan ke Pengepul</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0f5132;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            color: #0f5132;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0 0 0;
            font-size: 9px;
            color: #555;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }

        .table-data th {
            background-color: #f2f7f4;
            font-weight: bold;
            color: #0f5132;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SISTEM INFORMASI BANK SAMPAH</h2>
        <p>Rekapitulasi Penjualan Sampah Gudang ke Mitra Pengepul</p>
        <p style="font-size: 8px; color: #777;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 20px;">No</th>
                <th style="width: 85px;">No. Nota</th>
                <th style="width: 75px;">Tanggal</th>
                <th>Mitra Pengepul</th>
                <th>Rincian Muatan (Multi-Item)</th>
                <th class="text-right">Penjualan Kotor</th>
                <th class="text-right">Operasional</th>
                <th class="text-right">Kas Masuk Bersih</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_penjualan }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->pengepul->nama_pengepul ?? '-' }}</strong></td>
                    <td>
                        @foreach ($item->details as $d)
                            • {{ $d->jenisSampah->nama_jenis ?? '-' }} ({{ $d->jumlah }}
                            {{ $d->jenisSampah->satuan->simbol ?? 'kg' }}): Rp
                            {{ number_format($d->subtotal, 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #b02a37;">
                        {{ $item->biaya_operasional > 0 ? '- Rp ' . number_format($item->biaya_operasional, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right" style="color: #0f5132; font-weight: bold;">
                        + Rp {{ number_format($item->total_kas_bersih, 0, ',', '.') }}
                    </td>
                    <td>{{ $item->petugas->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Tidak ada data penjualan pada periode
                        ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right">Rp {{ number_format($totalKotor, 0, ',', '.') }}</td>
                <td class="text-right" style="color: #b02a37;">- Rp {{ number_format($totalOperasional, 0, ',', '.') }}
                </td>
                <td class="text-right" style="color: #0f5132;">Rp {{ number_format($totalKasBersih, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td class="text-center">
                <p>Pengelola Bank Sampah,</p>
                <br><br><br>
                <p><strong>( ..................................... )</strong><br>Koordinator Logistik & Gudang</p>
            </td>
        </tr>
    </table>
</body>

</html>
