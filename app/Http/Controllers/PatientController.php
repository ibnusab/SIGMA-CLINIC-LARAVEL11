<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mr_number', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(PatientRequest $request)
    {
        $validated = $request->validated();
        
        // Auto Generate MR Number (RM-YYYYMM-XXXX)
        $prefix = 'RM-' . Carbon::now()->format('Ym') . '-';
        $lastPatient = Patient::where('mr_number', 'like', $prefix . '%')->latest('id')->first();
        $nextNumber = $lastPatient ? ((int) substr($lastPatient->mr_number, -4)) + 1 : 1;
        $validated['mr_number'] = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data Pasien ' . $patient->name . ' berhasil ditambahkan dengan No. RM: ' . $patient->mr_number);
    }

    public function show(Patient $patient)
    {
        $patient->load(['registrations.doctor', 'registrations.clinic', 'medicalRecords.doctor', 'medicalRecords.prescriptions']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data Pasien ' . $patient->name . ' berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        $name = $patient->name;
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Data Pasien ' . $name . ' berhasil dihapus.');
    }

    public function printCard(Patient $patient)
    {
        return view('patients.card', compact('patient'));
    }
}
