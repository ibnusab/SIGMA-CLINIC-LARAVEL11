<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ClinicSeeder::class,
            DoctorSeeder::class,
            ScheduleSeeder::class,
            SupplierSeeder::class,
            MedicineSeeder::class,
            TreatmentSeeder::class,
            PatientSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
