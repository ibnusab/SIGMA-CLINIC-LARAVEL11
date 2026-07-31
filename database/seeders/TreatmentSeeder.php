<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $treatments = [
            ['code' => 'TND-001', 'name' => 'Pembersihan Karang Gigi (Scaling)', 'price' => 150000, 'description' => 'Pembersihan plak dan tartar gigi atas dan bawah'],
            ['code' => 'TND-002', 'name' => 'Penambalan Gigi Composite', 'price' => 120000, 'description' => 'Penambalan estetika bahan komposit warna gigi'],
            ['code' => 'TND-003', 'name' => 'Pemeriksaan EKG Jantung', 'price' => 100000, 'description' => 'Pemeriksaan rekam irama jantung 12 lead'],
            ['code' => 'TND-004', 'name' => 'Cek Gula Darah Sewaktu', 'price' => 25000, 'description' => 'Pemeriksaan kadar glukosa darah rapid tes'],
            ['code' => 'TND-005', 'name' => 'Suntik Vitamin B Complex', 'price' => 35000, 'description' => 'Injeksi vitamin neurotropik intramuskular'],
            ['code' => 'TND-006', 'name' => 'Jahit Luka Ringan (1-3 jahitan)', 'price' => 85000, 'description' => 'Tindakan bedah minor penjahitan luka superficial'],
        ];

        foreach ($treatments as $t) {
            Treatment::updateOrCreate(['code' => $t['code']], $t);
        }
    }
}
