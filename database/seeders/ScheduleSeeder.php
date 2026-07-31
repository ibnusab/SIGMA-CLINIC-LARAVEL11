<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Doctor;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doc) {
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            foreach ($days as $day) {
                Schedule::updateOrCreate(
                    [
                        'doctor_id' => $doc->id,
                        'day' => $day,
                        'start_time' => '08:00:00',
                    ],
                    [
                        'end_time' => '14:00:00',
                        'quota' => 25,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
