<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #999; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Bank Sampah</h2>
        <p>Solusi Bersih & Tabungan</p>
        <h3 style="margin-top: 15px;">{{ $title }}</h3>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    @if(isset($data) && $data->count() > 0)
        <table>
            <thead>
                @if(isset($type) && $type === 'setoran')
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nasabah</th>
                        <th class="text-center">Total Berat (Kg)</th>
                        <th class="text-right">Total Harga (Rp)</th>
                    </tr>
                @elseif(isset($type) && $type === 'penarikan')
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nasabah</th>
                        <th class="text-right">Jumlah Penarikan (Rp)</th>
                        <th class="text-center">Status</th>
                    </tr>
                @elseif(isset($type) && $type === 'saldo')
                    <tr>
                        <th>No</th>
                        <th>ID Nasabah</th>
                        <th>Nama Nasabah</th>
                        <th class="text-right">Total Saldo Aktif (Rp)</th>
                    </tr>
                @elseif(isset($type) && $type === 'sampah')
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th class="text-center">Total Berat (Kg)</th>
                        <th class="text-right">Estimasi Nilai (Rp)</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    @if(isset($type) && $type === 'setoran')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->nasabah->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->total_berat }}</td>
                            <td class="text-right">{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @elseif(isset($type) && $type === 'penarikan')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->nasabah->name ?? '-' }}</td>
                            <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="text-center">{{ ucfirst($item->status) }}</td>
                        </tr>
                    @elseif(isset($type) && $type === 'saldo')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                    @elseif(isset($type) && $type === 'sampah')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="text-center">{{ $item->berat }}</td>
                            <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center">Data belum tersedia pada laporan ini.</p>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }} ({{ ucfirst(auth()->user()->role ?? 'Admin') }})</p>
    </div>

</body>
</html>
