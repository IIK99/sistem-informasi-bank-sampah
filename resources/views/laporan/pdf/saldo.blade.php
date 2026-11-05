<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Saldo Nasabah</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #17a2b8;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            color: #117a8b;
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
            background-color: #f1fbfc;
            color: #117a8b;
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
        <p>Laporan Rekapitulasi Saldo & Kewajiban Kas Simpanan Warga</p>
        <p style="font-size: 9px; color: #888;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Nama Nasabah</th>
                <th>Username</th>
                <th class="text-right">Total Setor Masuk</th>
                <th class="text-right">Total Ditarik Keluar</th>
                <th class="text-right">Sisa Saldo Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nasabahs as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td>{{ $item->username }}</td>
                    <td class="text-right" style="color: #28a745;">+ Rp
                        {{ number_format($item->total_setoran, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #dc3545;">- Rp
                        {{ number_format($item->total_penarikan, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #117a8b;">Rp
                        {{ number_format($item->saldo_aktif, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data warga terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #fafafa; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KEWAJIBAN KAS KELUAR KE WARGA:</td>
                <td class="text-right" style="color: #117a8b;">Rp {{ number_format($totalKasWarga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td class="text-center">
                <p>Pengelola Bank Sampah,</p>
                <br><br><br>
                <p><strong>( ..................................... )</strong><br>Admin Sistem</p>
            </td>
        </tr>
    </table>
</body>

</html>
