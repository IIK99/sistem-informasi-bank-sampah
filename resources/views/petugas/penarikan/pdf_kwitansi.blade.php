<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Penarikan Saldo - {{ $penarikan->kode_penarikan }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #2d3748;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-title h2 {
            margin: 0 0 3px 0;
            color: #1b5e20;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-title p {
            margin: 0;
            color: #4a5568;
            font-size: 10px;
        }

        .receipt-badge {
            text-align: right;
        }

        .badge-status {
            display: inline-block;
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .receipt-code {
            font-size: 12px;
            font-weight: bold;
            color: #1b5e20;
            margin-top: 4px;
        }

        .title-box {
            text-align: center;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .title-box h3 {
            margin: 0;
            color: #2d3748;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-info td {
            padding: 6px 10px;
            vertical-align: top;
            font-size: 11px;
        }

        .table-info td.label {
            width: 25%;
            color: #718096;
            font-weight: bold;
            background-color: #f8fafc;
            border: 1px solid #edf2f7;
        }

        .table-info td.val {
            width: 75%;
            border: 1px solid #edf2f7;
            color: #1a202c;
        }

        .nominal-highlight {
            font-size: 16px;
            font-weight: bold;
            color: #c53030;
        }

        .bukti-container {
            margin-top: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            background-color: #ffffff;
            page-break-inside: avoid;
        }

        .bukti-header {
            font-size: 12px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #cbd5e0;
        }

        .bukti-image-box {
            text-align: center;
            padding: 8px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .bukti-img {
            max-width: 440px;
            max-height: 380px;
            border-radius: 4px;
            border: 1px solid #cbd5e0;
        }

        .bukti-doc-box {
            padding: 15px;
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 4px;
            text-align: center;
            color: #2b6cb0;
        }

        .footer-sign {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .footer-sign td {
            vertical-align: top;
        }

        .sign-box {
            text-align: center;
            width: 40%;
        }

        .sign-line {
            margin-top: 55px;
            border-top: 1px solid #4a5568;
            font-weight: bold;
            padding-top: 4px;
        }

        .disclaimer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #a0aec0;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- HEADER SISTEM --}}
    <table class="header-table">
        <tr>
            <td style="width: 60%;" class="header-title">
                <h2>SISTEM INFORMASI BANK SAMPAH</h2>
                <p>Pengelolaan Tabungan & Pencairan Saldo Sampah Warga</p>
                <p style="font-size: 9px; color: #718096; margin-top: 2px;">Waktu Cetak: {{ date('d F Y, H:i') }} WIB</p>
            </td>
            <td style="width: 40%;" class="receipt-badge">
                <div class="badge-status">
                    STATUS: {{ strtoupper($penarikan->status) }}
                </div>
                <div class="receipt-code">{{ $penarikan->kode_penarikan }}</div>
            </td>
        </tr>
    </table>

    {{-- JUDUL KWITANSI --}}
    <div class="title-box">
        <h3>KWITANSI BUKTI PENARIKAN SALDO</h3>
    </div>

    {{-- DETAIL TRANSAKSI --}}
    <table class="table-info">
        <tr>
            <td class="label">Kode Transaksi</td>
            <td class="val"><strong style="color: #1b5e20;">{{ $penarikan->kode_penarikan }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Nasabah</td>
            <td class="val">
                <strong>{{ $penarikan->nasabah->name ?? '-' }}</strong>
                @if (isset($penarikan->nasabah->username))
                    <span style="color: #718096;">({{ $penarikan->nasabah->username }})</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Nominal Dicairkan</td>
            <td class="val">
                <span class="nominal-highlight">Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">Waktu Pengajuan</td>
            <td class="val">{{ $penarikan->created_at->format('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Waktu Disetujui (ACC)</td>
            <td class="val">
                {{ $penarikan->tanggal_proses ? $penarikan->tanggal_proses->format('d F Y, H:i') . ' WIB' : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Petugas Verifikator</td>
            <td class="val">
                <strong>{{ $penarikan->petugas->name ?? 'Petugas Bank Sampah' }}</strong>
                @if (isset($penarikan->petugas->email))
                    <span style="color: #718096;">({{ $penarikan->petugas->email }})</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Catatan Nasabah</td>
            <td class="val">{{ $penarikan->catatan_nasabah ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Catatan Petugas</td>
            <td class="val">{{ $penarikan->catatan_petugas ?: '-' }}</td>
        </tr>
    </table>

    {{-- LAMPIRAN BUKTI TRANSFER --}}
    <div class="bukti-container">
        <div class="bukti-header">
            LAMPIRAN BUKTI TRANSFER / PEMBAYARAN:
        </div>

        @if ($isImage && $buktiBase64)
            <div class="bukti-image-box">
                <img src="{{ $buktiBase64 }}" alt="Bukti Transfer" class="bukti-img">
                <p style="margin: 6px 0 0 0; font-size: 9px; color: #718096;">
                    Lampiran Dokumen Bukti Transfer Resmi #{{ $penarikan->kode_penarikan }}
                </p>
            </div>
        @elseif($penarikan->bukti_transfer)
            <div class="bukti-doc-box">
                <strong style="font-size: 12px;">Berkas Bukti Transfer Terlampir (Dokumen PDF)</strong>
                <p style="margin: 5px 0 0 0; font-size: 10px;">
                    Nama Berkas: {{ basename($penarikan->bukti_transfer) }}
                </p>
            </div>
        @else
            <div style="text-align: center; padding: 15px; color: #a0aec0; font-style: italic;">
                Tidak ada berkas bukti transfer fisik yang dilampirkan.
            </div>
        @endif
    </div>

    {{-- TANDA TANGAN --}}
    <table class="footer-sign">
        <tr>
            <td class="sign-box">
                <p style="margin: 0;">Penerima Dana (Nasabah),</p>
                <div class="sign-line">{{ $penarikan->nasabah->name ?? 'Nasabah' }}</div>
            </td>
            <td style="width: 20%;"></td>
            <td class="sign-box">
                <p style="margin: 0;">Petugas Kasir / Verifikator,</p>
                <div class="sign-line">{{ $penarikan->petugas->name ?? 'Petugas Pos' }}</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER / DISCLAIMER --}}
    <div class="disclaimer">
        Dokumen ini diterbitkan secara sah oleh Sistem Informasi Bank Sampah sebagai bukti transaksi penarikan saldo
        tabungan warga.
    </div>
</body>

</html>
