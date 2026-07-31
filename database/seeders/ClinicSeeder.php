<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = [
            ['name' => 'Poli Umum', 'code' => 'POL-UMM', 'location' => 'Lantai 1, Ruang 101', 'description' => 'Pelayanan kesehatan umum dan konsultasi medis dasar'],
            ['name' => 'Poli Gigi & Mulut', 'code' => 'POL-GGI', 'location' => 'Lantai 1, Ruang 102', 'description' => 'Pemeriksaan gigi, pembersihan karang, dan perawatan spesialis mulut'],
            ['name' => 'Poli Anak (Pediatri)', 'code' => 'POL-ANK', 'location' => 'Lantai 2, Ruang 201', 'description' => 'Spesialis kesehatan bayi, anak, imunisasi, dan tumbuh kembang'],
            ['name' => 'Poli Penyakit Dalam', 'code' => 'POL-PDL', 'location' => 'Lantai 2, Ruang 202', 'description' => 'Konsultasi spesialis organ dalam, diabetes, dan hipertensi'],
        ];

        foreach ($clinics as $c) {
            Clinic::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
