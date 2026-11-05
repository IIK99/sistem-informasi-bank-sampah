<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Pengajuan Penarikan - {{ $penarikan->kode_penarikan }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 15px; }
        .kop { border-bottom: 2px solid #16a34a; padding-bottom: 10px; margin-bottom: 15px; }
        .kop h2 { margin: 0; color: #15803d; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; color: #718096; font-size: 10px; }
        .title-box { text-align: center; margin: 15px 0; }
        .title-box h3 { margin: 0; font-size: 14px; text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px; }
        .title-box span { font-size: 11px; color: #16a34a; font-weight: bold; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-data th, .table-data td { padding: 8px 10px; border: 1px solid #cbd5e1; }
        .table-data th { background-color: #f1f5f9; text-align: left; width: 35%; font-weight: 600; }
        .amount-box { background: #f0fdf4; border: 1px dashed #22c55e; padding: 12px; margin: 15px 0; border-radius: 6px; text-align: center; }
        .amount-box h4 { margin: 0; color: #15803d; font-size: 18px; }
        .amount-box p { margin: 3px 0 0 0; font-size: 10px; color: #64748b; }
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
        <h3>FORMULIR PENGAJUAN PENARIKAN SALDO</h3>
        <span>No. Registrasi: {{ $penarikan->kode_penarikan }}</span>
    </div>

    <table class="table-data">
        <tr>
            <th>Waktu Pengajuan</th>
            <td>{{ $penarikan->created_at->format('d F Y, H:i:s') }} WIB</td>
        </tr>
        <tr>
            <th>Nama Nasabah</th>
            <td><strong>{{ $nasabah->name }}</strong></td>
        </tr>
        <tr>
            <th>Username / ID Warga</th>
            <td>{{ $nasabah->username }}</td>
        </tr>
        <tr>
            <th>Email Nasabah</th>
            <td>{{ $nasabah->email ?: '-' }}</td>
        </tr>
        <tr>
            <th>Catatan / Keterangan</th>
            <td>{{ $penarikan->catatan_nasabah ?: 'Tidak ada catatan khusus.' }}</td>
        </tr>
        <tr>
            <th>Status Permintaan</th>
            <td><strong style="color: #d97706; text-transform: uppercase;">{{ $penarikan->status }}</strong></td>
        </tr>
    </table>

    <div class="amount-box">
        <p>NOMINAL YANG DIAJUKAN:</p>
        <h4>Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</h4>
        <p>Sisa Saldo Tabungan: Rp {{ number_format($nasabah->saldo, 0, ',', '.') }}</p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Pemohon (Nasabah),<br><br><br><br>
                <strong><u>{{ $nasabah->name }}</u></strong>
            </td>
            <td>
                Petugas Verifikator,<br><br><br><br>
                <strong><u>( ......................................... )</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
