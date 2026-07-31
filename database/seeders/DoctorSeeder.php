<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Clinic;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'dokter.andri@sigmaclinic.com')->first();
        $user2 = User::where('email', 'dokter.siti@sigmaclinic.com')->first();
        $poliPdl = Clinic::where('code', 'POL-PDL')->first();
        $poliGgi = Clinic::where('code', 'POL-GGI')->first();

        if ($user1 && $poliPdl) {
            Doctor::updateOrCreate(
                ['nip_sip' => 'SIP-2026/01/440/PDL'],
                [
                    'user_id' => $user1->id,
                    'clinic_id' => $poliPdl->id,
                    'name' => 'dr. Andri Pratama, Sp.PD',
                    'specialization' => 'Spesialis Penyakit Dalam',
                    'phone' => '081298765432',
                    'consultation_fee' => 75000,
                    'is_active' => true,
                ]
            );
        }

        if ($user2 && $poliGgi) {
            Doctor::updateOrCreate(
                ['nip_sip' => 'SIP-2026/02/440/GGI'],
                [
                    'user_id' => $user2->id,
                    'clinic_id' => $poliGgi->id,
                    'name' => 'drg. Siti Rahmawati',
                    'specialization' => 'Spesialis Konservasi Gigi',
                    'phone' => '081388887777',
                    'consultation_fee' => 60000,
                    'is_active' => true,
                ]
            );
        }
    }
}
