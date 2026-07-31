<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'mr_number' => 'RM-202607-0001',
                'nik' => '3171011508850001',
                'name' => 'Bambang Sugiono',
                'gender' => 'L',
                'birth_date' => '1985-08-15',
                'address' => 'Jl. Kebon Jeruk No. 12, Kebayoran Lama, Jakarta Selatan',
                'phone' => '081211112222',
                'blood_type' => 'O',
                'allergies' => 'Alergi Seafood, Penisilin',
            ],
            [
                'mr_number' => 'RM-202607-0002',
                'nik' => '3171024205920003',
                'name' => 'Siti Nurhaliza',
                'gender' => 'P',
                'birth_date' => '1992-05-22',
                'address' => 'Jl. Tebet Barat Dalam No. 45, Tebet, Jakarta Selatan',
                'phone' => '081333334444',
                'blood_type' => 'A',
                'allergies' => 'Tidak ada',
            ],
            [
                'mr_number' => 'RM-202607-0003',
                'nik' => '3171031011780005',
                'name' => 'Hendra Wijaya',
                'gender' => 'L',
                'birth_date' => '1978-11-10',
                'address' => 'Jl. Cilandak KKO No. 8, Pasar Minggu, Jakarta Selatan',
                'phone' => '081555556666',
                'blood_type' => 'B',
                'allergies' => 'Debu, Obat Sulfa',
            ],
            [
                'mr_number' => 'RM-202607-0004',
                'nik' => '3171046803000002',
                'name' => 'Anisa Putri',
                'gender' => 'P',
                'birth_date' => '2000-03-28',
                'address' => 'Jl. Radio Dalam No. 88, Kebayoran Baru, Jakarta Selatan',
                'phone' => '081777778888',
                'blood_type' => 'AB',
                'allergies' => 'Tidak ada',
            ],
        ];

        foreach ($patients as $p) {
            Patient::updateOrCreate(['mr_number' => $p['mr_number']], $p);
        }
    }
}
