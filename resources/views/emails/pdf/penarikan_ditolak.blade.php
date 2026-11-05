<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pemberitahuan Penolakan Penarikan - {{ $penarikan->kode_penarikan }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 15px; }
        .kop { border-bottom: 2px solid #dc2626; padding-bottom: 10px; margin-bottom: 15px; }
        .kop h2 { margin: 0; color: #b91c1c; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; color: #718096; font-size: 10px; }
        .title-box { text-align: center; margin: 15px 0; }
        .title-box h3 { margin: 0; font-size: 14px; text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px; }
        .title-box span { font-size: 11px; color: #dc2626; font-weight: bold; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-data th, .table-data td { padding: 8px 10px; border: 1px solid #cbd5e1; }
        .table-data th { background-color: #f1f5f9; text-align: left; width: 35%; font-weight: 600; }
        .alert-box { background: #fef2f2; border: 1px dashed #f87171; padding: 12px; margin: 15px 0; border-radius: 6px; text-align: center; }
        .alert-box h4 { margin: 0; color: #b91c1c; font-size: 16px; }
        .alert-box p { margin: 5px 0 0 0; font-size: 11px; color: #7f1d1d; }
        .signature-table { width: 100%; margin-top: 35px; border-collapse: collapse; }
        .signature-table td { width: 50%; text-align: center; font-size: 11px; border: none; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>BANK SAMPAH RW 012</h2>
        <p>Desa Curug Kulon, Kecamatan Curug, Kabupaten Tangerang, Banten</p>
        <p>Telepon / WA: 0812-3456-7890 | Email: banksampahcurug@gmail.com</p>
    </div>

    <div class="title-box">
        <h3>SURAT PEMBERITAHUAN PENOLAKAN PENARIKAN SALDO</h3>
        <span>Nomor: {{ $penarikan->kode_penarikan }}</span>
    </div>

    <table class="table-data">
        <tr>
            <th>Waktu Keputusan</th>
            <td>{{ optional($penarikan->tanggal_proses ?? now())->format('d F Y, H:i:s') }} WIB</td>
        </tr>
        <tr>
            <th>Nama Nasabah</th>
            <td><strong>{{ $nasabah->name }}</strong></td>
        </tr>
        <tr>
            <th>Nominal Pengajuan</th>
            <td>Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Status Penarikan</th>
            <td><strong style="color: #dc2626; text-transform: uppercase;">DITOLAK</strong></td>
        </tr>
        <tr>
            <th>Alasan Penolakan</th>
            <td style="color: #b91c1c; font-weight: bold;">{{ $penarikan->catatan_petugas ?: 'Data tidak sesuai atau saldo tidak mencukupi.' }}</td>
        </tr>
        <tr>
            <th>Petugas Pemeriksa</th>
            <td>{{ $penarikan->petugas->name ?? 'Petugas Bank Sampah' }}</td>
        </tr>
    </table>

    <div class="alert-box">
        <h4>SALDO TIDAK TERPOTONG</h4>
        <p>Saldo tabungan Anda saat ini tetap aman sejumlah Rp {{ number_format($nasabah->saldo, 0, ',', '.') }}. Anda dapat mengajukan kembali penarikan saldo sesuai ketentuan yang berlaku.</p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Nasabah,<br><br><br><br>
                <strong><u>{{ $nasabah->name }}</u></strong>
            </td>
            <td>
                Petugas Pemeriksa,<br><br><br><br>
                <strong><u>{{ $penarikan->petugas->name ?? 'Pengurus Bank Sampah' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
