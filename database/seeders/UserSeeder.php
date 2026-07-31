<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $dokterRole = Role::where('slug', 'dokter')->first();
        $resepsionisRole = Role::where('slug', 'resepsionis')->first();
        $apotekerRole = Role::where('slug', 'apoteker')->first();

        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@sigmaclinic.com'],
            [
                'role_id' => $adminRole?->id,
                'name' => 'Budi Santoso, S.Kom (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // 2. Dokter 1
        User::updateOrCreate(
            ['email' => 'dokter.andri@sigmaclinic.com'],
            [
                'role_id' => $dokterRole?->id,
                'name' => 'dr. Andri Pratama, Sp.PD',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'phone' => '081298765432',
                'is_active' => true,
            ]
        );

        // 3. Dokter 2
        User::updateOrCreate(
            ['email' => 'dokter.siti@sigmaclinic.com'],
            [
                'role_id' => $dokterRole?->id,
                'name' => 'drg. Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'phone' => '081388887777',
                'is_active' => true,
            ]
        );

        // 4. Resepsionis
        User::updateOrCreate(
            ['email' => 'resepsionis@sigmaclinic.com'],
            [
                'role_id' => $resepsionisRole?->id,
                'name' => 'Dewi Lestari (Frontdesk)',
                'password' => Hash::make('password'),
                'role' => 'resepsionis',
                'phone' => '081566665555',
                'is_active' => true,
            ]
        );

        // 5. Apoteker
        User::updateOrCreate(
            ['email' => 'apoteker@sigmaclinic.com'],
            [
                'role_id' => $apotekerRole?->id,
                'name' => 'Apt. Rian Hidayat, S.Farm',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'phone' => '081744443333',
                'is_active' => true,
            ]
        );
    }
}
