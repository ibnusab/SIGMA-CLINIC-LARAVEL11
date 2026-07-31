<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #1e293b; line-height: 1.3; }
        .header { border-bottom: 2px solid #0284c7; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { margin: 0; font-size: 16px; color: #0f172a; }
        .header p { margin: 2px 0 0; font-size: 9px; color: #64748b; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #cbd5e1; padding: 5px; text-align: left; }
        .table th { background-color: #f8fafc; font-weight: bold; }
        .title { font-size: 13px; font-weight: bold; margin-bottom: 8px; text-align: center; color: #0369a1; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 70%;">
                    <h1>SIGMA CLINIC UTAMA</h1>
                    <p>Jl. Kesehatan No. 88, Jakarta Selatan | Telp: (021) 7890123</p>
                </td>
                <td style="border: none; text-align: right;">
                    <strong style="font-size: 11px; color: #0284c7;">DOKUMEN LAPORAN RESMI</strong><br>
                    <span>Dicetak: {{ date('d-m-Y H:i') }} WIB</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">{{ $title }}</div>

    @if($type === 'kunjungan')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. Antrian / Reg</th>
                <th>Nama Pasien</th>
                <th>Poli Klinik</th>
                <th>Dokter</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($row->registration_date)->format('d-m-Y') }}</td>
                <td>#{{ str_pad($row->queue_number, 2, '0', STR_PAD_LEFT) }} ({{ $row->registration_number }})</td>
                <td>{{ $row->patient->name ?? '-' }} (RM: {{ $row->patient->mr_number ?? '-' }})</td>
                <td>{{ $row->clinic->name ?? '-' }}</td>
                <td>{{ $row->doctor->name ?? '-' }}</td>
                <td>{{ strtoupper($row->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @elseif($type === 'pendapatan')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Bayar</th>
                <th>No. Transaksi</th>
                <th>Nama Pasien</th>
                <th>Metode Bayar</th>
                <th style="text-align: right;">Total Omzet (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $total = 0; @endphp
            @foreach($data as $row)
            @php $total += $row->total_amount; @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($row->paid_at)->format('d-m-Y H:i') }}</td>
                <td>{{ $row->payment_number }}</td>
                <td>{{ $row->patient->name ?? '-' }}</td>
                <td style="text-transform: uppercase;">{{ $row->payment_method }}</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($row->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #0f172a; color: white; font-weight: bold;">
                <td colspan="5" style="text-align: right;">TOTAL PENDAPATAN REKAPITULASI:</td>
                <td style="text-align: right;">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Modal</th>
                <th>Harga Jual</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->code }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->category }}</td>
                <td>{{ $row->stock }} {{ $row->unit }}</td>
                <td>Rp {{ number_format($row->purchase_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->selling_price, 0, ',', '.') }}</td>
                <td>{{ $row->supplier->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr style="border: none;">
            <td style="border: none; width: 70%;"></td>
            <td style="border: none; text-align: center;">
                Jakarta, {{ date('d F Y') }}<br>
                Kepala Klinik SIGMA,<br><br><br><br>
                <strong><u>dr. Hj. Farida Utama, M.Kes</u></strong><br>
                NIP: 198004122005012001
            </td>
        </tr>
    </table>

</body>
</html>
