<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['clinic', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_sip', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        $doctors = $query->paginate(10)->withQueryString();
        $clinics = Clinic::where('is_active', true)->get();

        return view('doctors.index', compact('doctors', 'clinics'));
    }

    public function create()
    {
        $clinics = Clinic::where('is_active', true)->get();
        return view('doctors.create', compact('clinics'));
    }

    public function store(DoctorRequest $request)
    {
        $validated = $request->validated();
        $dokterRole = Role::where('slug', 'dokter')->first();

        // 1. Create User Account
        $user = User::create([
            'role_id' => $dokterRole?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dokter',
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);

        // 2. Handle Photo Upload if present
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('doctors', 'public');
        }

        // 3. Create Doctor Record
        Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => $validated['clinic_id'],
            'nip_sip' => $validated['nip_sip'],
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'phone' => $validated['phone'],
            'consultation_fee' => $validated['consultation_fee'],
            'photo' => $photoPath,
            'is_active' => true,
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Data Dokter ' . $validated['name'] . ' beserta akun login berhasil dibuat.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['clinic', 'schedules', 'registrations.patient', 'medicalRecords']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $clinics = Clinic::where('is_active', true)->get();
        $doctor->load('user');
        return view('doctors.edit', compact('doctor', 'clinics'));
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        $validated = $request->validated();

        // Update User
        $doctor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $doctor->user->password,
        ]);

        // Photo Upload
        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $doctor->photo = $request->file('photo')->store('doctors', 'public');
        }

        // Update Doctor
        $doctor->update([
            'clinic_id' => $validated['clinic_id'],
            'nip_sip' => $validated['nip_sip'],
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'phone' => $validated['phone'],
            'consultation_fee' => $validated['consultation_fee'],
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Data Dokter ' . $doctor->name . ' berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $name = $doctor->name;
        if ($doctor->user) {
            $doctor->user->delete();
        }
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Data Dokter ' . $name . ' berhasil dihapus.');
    }
}
