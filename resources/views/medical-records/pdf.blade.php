<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekam Medis - {{ $medicalRecord->record_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { border-bottom: 2px solid #0284c7; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { margin: 0; font-size: 16px; color: #0f172a; }
        .header p { margin: 2px 0 0; font-size: 9px; color: #64748b; }
        .table { w-full; border-collapse: collapse; margin-bottom: 12px; width: 100%; }
        .table th, .table td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; }
        .table th { background-color: #f8fafc; font-weight: bold; }
        .title { font-size: 13px; font-weight: bold; margin-bottom: 10px; text-align: center; color: #0369a1; text-transform: uppercase; }
        .box { background-color: #f1f5f9; border: 1px solid #e2e8f0; padding: 8px; border-radius: 4px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 70%;">
                    <h1>SIGMA CLINIC UTAMA</h1>
                    <p>Jl. Kesehatan No. 88, Jakarta Selatan | Telp: (021) 7890123</p>
                    <p>SIP Klinik: 440/123/DISKES/2026</p>
                </td>
                <td style="border: none; text-align: right;">
                    <strong style="font-size: 12px; color: #0284c7;">LEMBAR REKAM MEDIS</strong><br>
                    <span style="font-family: monospace;">{{ $medicalRecord->record_number }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <strong>IDENTITAS PASIEN:</strong><br>
                    Nama: {{ $medicalRecord->patient->name ?? '-' }}<br>
                    No. RM: {{ $medicalRecord->patient->mr_number ?? '-' }}<br>
                    NIK: {{ $medicalRecord->patient->nik ?? '-' }}<br>
                    Jenis Kelamin: {{ ($medicalRecord->patient->gender ?? 'L') === 'L' ? 'Laki-laki' : 'Perempuan' }} ({{ $medicalRecord->patient->age ?? '-' }} Th)
                </td>
                <td style="border: none; width: 50%;">
                    <strong>DOKTER PEMERIKSA:</strong><br>
                    Dokter: {{ $medicalRecord->doctor->name ?? '-' }}<br>
                    Poli: {{ $medicalRecord->doctor->clinic->name ?? 'Poli Umum' }}<br>
                    SIP: {{ $medicalRecord->doctor->nip_sip ?? '-' }}<br>
                    Tanggal/Waktu: {{ \Carbon\Carbon::parse($medicalRecord->examination_date)->format('d-m-Y H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>1. TANDA-TANDA VITAL & ANAMNESA:</strong>
        <p style="margin: 4px 0;"><strong>TD:</strong> {{ $medicalRecord->blood_pressure ?? '-' }} mmHg | <strong>Suhu:</strong> {{ $medicalRecord->temperature ?? '-' }} &deg;C | <strong>TB/BB:</strong> {{ $medicalRecord->height ?? '-' }} cm / {{ $medicalRecord->weight ?? '-' }} kg | <strong>IMT:</strong> {{ $medicalRecord->bmi ?? '-' }}</p>
        <p style="margin: 4px 0;"><strong>Keluhan Utama:</strong> {{ $medicalRecord->complaints }}</p>
        <p style="margin: 4px 0;"><strong>Pemeriksaan Fisik:</strong> {{ $medicalRecord->physical_exam ?? '-' }}</p>
    </div>

    <div style="margin-bottom: 10px; background-color: #fef3c7; border: 1px solid #fde68a; padding: 6px; border-radius: 4px;">
        <strong style="color: #92400e;">2. DIAGNOSA UTAMA DOKTER:</strong>
        <p style="margin: 2px 0; font-size: 12px; font-weight: bold; color: #78350f;">{{ $medicalRecord->diagnosis }}</p>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>3. TINDAKAN MEDIS DIBERIKAN:</strong>
        <ul style="margin: 4px 0; padding-left: 18px;">
            @forelse($medicalRecord->treatments as $t)
            <li>{{ $t->name }}</li>
            @empty
            <li>Pemeriksaan fisik standar</li>
            @endforelse
        </ul>
    </div>

    <div style="margin-bottom: 15px;">
        <strong>4. RESEP OBAT APOTEK:</strong>
        <table class="table" style="margin-top: 4px;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Aturan Pakai / Dosis</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($medicalRecord->prescriptions as $p)
                    @foreach($p->items as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->medicine->name ?? '-' }}</td>
                        <td>{{ $item->quantity }} {{ $item->medicine->unit ?? 'Pcs' }}</td>
                        <td>{{ $item->instruction }}</td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada resep obat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr style="border: none;">
            <td style="border: none; width: 60%;">
                <p>Catatan Dokter: {{ $medicalRecord->doctor_notes ?? '-' }}</p>
            </td>
            <td style="border: none; text-align: center;">
                Jakarta, {{ \Carbon\Carbon::parse($medicalRecord->examination_date)->format('d F Y') }}<br>
                Dokter Pemeriksa,<br><br><br><br>
                <strong><u>{{ $medicalRecord->doctor->name ?? '-' }}</u></strong><br>
                SIP: {{ $medicalRecord->doctor->nip_sip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
