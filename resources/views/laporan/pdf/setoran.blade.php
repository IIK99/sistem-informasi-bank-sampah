<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Setoran Sampah</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            color: #155724;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0 0 0;
            color: #666;
            font-size: 10px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        .table-data th {
            background-color: #f2f9f4;
            color: #155724;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-box {
            margin-top: 15px;
            width: 100%;
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
        <p>Laporan Rekapitulasi Penimbangan & Setoran Sampah Warga</p>
        <p style="font-size: 9px; color: #888;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Nama Nasabah</th>
                <th>Jenis Sampah</th>
                <th class="text-right">Berat (Kg)</th>
                <th class="text-right">Total Nilai (Rp)</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($setorans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_setoran }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->nasabah->name ?? '-' }}</strong></td>
                    <td>{{ $item->jenis_sampah }}</td>
                    <td class="text-right">{{ number_format($item->berat, 2, ',', '.') }} kg</td>
                    <td class="text-right" style="color: #28a745; font-weight: bold;">+ Rp
                        {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $item->petugas->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data setoran.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #fafafa; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right">{{ number_format($totalBerat, 2, ',', '.') }} kg</td>
                <td class="text-right" style="color: #28a745;">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
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
                <p><strong>( ..................................... )</strong><br>Petugas Operasional</p>
            </td>
        </tr>
    </table>
</body>

</html>
