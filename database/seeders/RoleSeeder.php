<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Akses penuh seluruh modul sistem'],
            ['name' => 'Dokter', 'slug' => 'dokter', 'description' => 'Akses pemeriksaan, rekam medis, dan resep'],
            ['name' => 'Resepsionis', 'slug' => 'resepsionis', 'description' => 'Akses pendaftaran, antrian, dan pembayaran'],
            ['name' => 'Apoteker', 'slug' => 'apoteker', 'description' => 'Akses kelola obat, resep, dan stok'],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
