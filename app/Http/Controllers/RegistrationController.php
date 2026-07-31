<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Schedule;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['patient', 'doctor', 'clinic']);

        if ($request->filled('date')) {
            $query->whereDate('registration_date', $request->date);
        } else {
            $query->whereDate('registration_date', Carbon::today());
        }

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->orderBy('queue_number', 'asc')->paginate(15)->withQueryString();
        $clinics = Clinic::where('is_active', true)->get();

        return view('registrations.index', compact('registrations', 'clinics'));
    }

    private function getIndonesianDay($date)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $dayIndex = Carbon::parse($date)->dayOfWeek;
        return $days[$dayIndex] ?? 'Senin';
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('name', 'asc')->get();
        $doctors = Doctor::with('clinic')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        $regDate = $request->get('date', date('Y-m-d'));
        $dayName = $this->getIndonesianDay($regDate);

        // Attach quota info to each doctor for the specified date
        foreach ($doctors as $doc) {
            $schedule = Schedule::where('doctor_id', $doc->id)
                ->where('day', $dayName)
                ->where('is_active', true)
                ->first();

            if ($schedule) {
                $registeredCount = Registration::where('doctor_id', $doc->id)
                    ->whereDate('registration_date', $regDate)
                    ->where('status', '!=', 'batal')
                    ->count();

                $doc->schedule_info = $schedule;
                $doc->registered_count = $registeredCount;
                $doc->remaining_quota = max(0, $schedule->quota - $registeredCount);
            } else {
                $doc->schedule_info = null;
                $doc->registered_count = 0;
                $doc->remaining_quota = null; // No active schedule on this day
            }
        }

        $selectedPatientId = $request->get('patient_id');
        $selectedPatient = $selectedPatientId ? Patient::find($selectedPatientId) : null;

        return view('registrations.create', compact('patients', 'doctors', 'clinics', 'selectedPatient', 'selectedPatientId', 'regDate', 'dayName'));
    }

    public function store(RegistrationRequest $request)
    {
        $validated = $request->validated();
        $regDate = $validated['registration_date'];
        $dayName = $this->getIndonesianDay($regDate);

        $doctor = Doctor::find($validated['doctor_id']);
        $clinicId = ($doctor && $doctor->clinic_id) ? $doctor->clinic_id : ($validated['clinic_id'] ?? null);

        // Find schedule for this doctor and day
        $schedule = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day', $dayName)
            ->where('is_active', true)
            ->first();

        // Check patient quota per day
        if ($schedule) {
            $todayRegistered = Registration::where('doctor_id', $validated['doctor_id'])
                ->whereDate('registration_date', $regDate)
                ->where('status', '!=', 'batal')
                ->count();

            if ($todayRegistered >= $schedule->quota) {
                $doctorName = $doctor ? $doctor->name : 'Dokter';
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'doctor_id' => "Kuota pendaftaran untuk {$doctorName} pada hari {$dayName} ({$regDate}) telah PENUH (Maksimal {$schedule->quota} pasien per hari, terisi {$todayRegistered} pasien)."
                    ]);
            }
        }

        // Auto calculate Queue Number for specific doctor & date
        $lastQueue = Registration::where('doctor_id', $validated['doctor_id'])
            ->whereDate('registration_date', $regDate)
            ->max('queue_number');
        
        $queueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Auto Registration Number (REG-YYYYMMDD-XXX)
        $prefix = 'REG-' . Carbon::parse($regDate)->format('Ymd') . '-';
        $totalToday = Registration::whereDate('registration_date', $regDate)->count();
        $regNum = $prefix . str_pad($totalToday + 1, 3, '0', STR_PAD_LEFT);

        $complaintText = $validated['complaints'] ?? $validated['complaint'] ?? $request->input('complaint') ?? $request->input('complaints') ?? null;
        $visitType = $validated['visit_type'] ?? $request->input('visit_type') ?? 'umum';
        if (empty($visitType)) {
            $visitType = 'umum';
        }

        $registration = Registration::create([
            'registration_number' => $regNum,
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'clinic_id' => $clinicId,
            'schedule_id' => $schedule ? $schedule->id : ($validated['schedule_id'] ?? null),
            'queue_number' => $queueNumber,
            'registration_date' => $regDate,
            'status' => 'menunggu',
            'complaints' => $complaintText,
            'visit_type' => $visitType,
        ]);

        return redirect()->route('registrations.show', $registration)
            ->with('success', 'Pendaftaran Berhasil! Nomor Antrian Pasien: ' . $queueNumber);
    }

    public function show(Registration $registration)
    {
        $registration->load(['patient', 'doctor.clinic', 'clinic', 'medicalRecord', 'payment']);
        return view('registrations.show', compact('registration'));
    }

    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,dipanggil,pemeriksaan,selesai,batal',
        ]);

        $registration->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status pendaftaran/antrian diperbarui menjadi ' . ucfirst($validated['status']));
    }

    public function printTicket(Registration $registration)
    {
        $registration->load(['patient', 'doctor', 'clinic']);
        return view('registrations.ticket', compact('registration'));
    }
}
