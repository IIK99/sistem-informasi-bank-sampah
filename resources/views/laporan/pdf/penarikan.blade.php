<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penarikan Saldo</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            color: #721c24;
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
            background-color: #fff5f5;
            color: #721c24;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
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
        <p>Laporan Rekapitulasi Penarikan Saldo Tabungan Warga</p>
        <p style="font-size: 9px; color: #888;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Kode Penarikan</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Nasabah</th>
                <th class="text-right">Nominal Tarik</th>
                <th class="text-center">Status</th>
                <th>Petugas Validasi</th>
                <th class="text-center">Bukti Transfer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penarikans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_penarikan }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->nasabah->name ?? '-' }}</strong></td>
                    <td class="text-right" style="color: #dc3545; font-weight: bold;">Rp
                        {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span
                            class="badge {{ $item->status === 'disetujui' ? 'badge-success' : ($item->status === 'menunggu' ? 'badge-warning' : 'badge-danger') }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td>{{ $item->petugas->name ?? '-' }}</td>
                    <td class="text-center">
                        @if(!empty($item->bukti_base64))
                            <img src="{{ $item->bukti_base64 }}" style="max-height: 40px; max-width: 60px; border-radius: 3px; border: 1px solid #ccc; display: block; margin: 0 auto 2px auto;">
                            <span style="font-size: 8px; color: #155724; font-weight: bold;">Terlampir</span>
                        @elseif($item->bukti_transfer)
                            <span class="badge badge-success">Terlampir</span>
                        @else
                            <span style="color: #999; font-size: 9px;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada riwayat penarikan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #fafafa; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL KAS KELUAR (STATUS DISETUJUI):</td>
                <td class="text-right" style="color: #dc3545;">Rp {{ number_format($totalDitarik, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td class="text-center">
                <p>Pengelola Bank Sampah,</p>
                <br><br><br>
                <p><strong>( ..................................... )</strong><br>Bendahara Kas</p>
            </td>
        </tr>
    </table>
</body>

</html>
