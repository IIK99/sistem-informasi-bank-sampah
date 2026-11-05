<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Akumulasi Sampah</title>
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
            padding: 7px 10px;
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

        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SISTEM INFORMASI BANK SAMPAH</h2>
        <p>Laporan Rekapitulasi Berat & Nilai Berdasarkan Jenis Sampah</p>
        <p style="font-size: 9px; color: #888;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Jenis Sampah Terkumpul</th>
                <th class="text-center">Frekuensi Transaksi</th>
                <th class="text-right">Total Berat (Kg)</th>
                <th class="text-right">Total Perputaran Rupiah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapSampah as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->jenisSampah->nama_jenis ?? '-' }}</strong></td>
                    <td class="text-center">{{ $item->total_transaksi }} Kali</td>
                    <td class="text-right">{{ number_format($item->total_berat, 2, ',', '.') }} kg</td>
                    <td class="text-right" style="font-weight: bold; color: #28a745;">Rp
                        {{ number_format($item->total_uang, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Belum ada sampah terkumpul.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #fafafa; font-weight: bold;">
                <td colspan="3" class="text-right">GRAND TOTAL:</td>
                <td class="text-right">{{ number_format($grandTotalBerat, 2, ',', '.') }} kg</td>
                <td class="text-right" style="color: #28a745;">Rp {{ number_format($grandTotalUang, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td class="text-center">
                <p>Pengelola Bank Sampah,</p>
                <br><br><br>
                <p><strong>( ..................................... )</strong><br>Koordinator Logistik</p>
            </td>
        </tr>
    </table>
</body>

</html>
