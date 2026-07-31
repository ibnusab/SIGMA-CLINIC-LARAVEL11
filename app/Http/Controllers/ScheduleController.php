<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Registration;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['doctor.clinic']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        $schedules = $query->paginate(15)->withQueryString();

        $daysMap = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $todayName = $daysMap[Carbon::today()->dayOfWeek] ?? 'Senin';
        $todayDate = Carbon::today()->toDateString();

        foreach ($schedules as $s) {
            $isToday = ($s->day === $todayName);
            $todayCount = 0;

            if ($isToday) {
                $todayCount = Registration::where('doctor_id', $s->doctor_id)
                    ->whereDate('registration_date', $todayDate)
                    ->where('status', '!=', 'batal')
                    ->count();
            }

            $s->today_count = $todayCount;
            $s->today_remaining = max(0, $s->quota - $todayCount);
            $s->is_today = $isToday;
        }

        $doctors = Doctor::where('is_active', true)->get();

        return view('schedules.index', compact('schedules', 'doctors', 'todayName', 'todayDate'));
    }

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        Schedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'quota' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
