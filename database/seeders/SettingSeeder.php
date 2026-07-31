<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'clinic_name', 'value' => 'SIGMA CLINIC UTAMA', 'description' => 'Nama Resmi Klinik'],
            ['key' => 'clinic_address', 'value' => 'Jl. Kesehatan No. 88, Jakarta Selatan, DKI Jakarta 12430', 'description' => 'Alamat Lengkap Klinik'],
            ['key' => 'clinic_phone', 'value' => '021-7890123 / 0811-9988-7766', 'description' => 'Nomor Telepon Hotline'],
            ['key' => 'clinic_email', 'value' => 'layanan@sigmaclinic.com', 'description' => 'Email Kontak Resmi'],
            ['key' => 'clinic_license', 'value' => 'SIP.440/123/DISKES/2026', 'description' => 'Nomor Izin Operasional Klinik'],
            ['key' => 'registration_fee', 'value' => '15000', 'description' => 'Biaya Biaya Administrasi Pendaftaran'],
            ['key' => 'tax_percentage', 'value' => '0', 'description' => 'Persentase Pajak Layanan Obat/Tindakan (%)'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
