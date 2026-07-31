<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['userRole', 'doctor.clinic']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        $rolesList = [
            'admin' => 'Administrator',
            'dokter' => 'Dokter Klinik',
            'apoteker' => 'Apoteker / Farmasi',
            'resepsionis' => 'Resepsionis / Front Office',
        ];

        return view('users.index', compact('users', 'rolesList'));
    }

    public function create()
    {
        $rolesList = [
            'admin' => 'Administrator (Akses Penuh)',
            'dokter' => 'Dokter Klinik (Pemeriksaan & Resep)',
            'apoteker' => 'Apoteker / Farmasi (Pengelolaan Obat)',
            'resepsionis' => 'Resepsionis / Front Office (Pendaftaran & Kasir)',
        ];

        $clinics = Clinic::where('is_active', true)->get();

        return view('users.create', compact('rolesList', 'clinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:admin,dokter,apoteker,resepsionis',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            // Optional fields if creating doctor role at the same time
            'clinic_id' => 'required_if:role,dokter|nullable|exists:clinics,id',
            'nip_sip' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        // Map role_id from roles table if available
        $roleRecord = Role::where('slug', $validated['role'])->orWhere('name', 'like', "%{$validated['role']}%")->first();

        $user = User::create([
            'role_id' => $roleRecord ? $roleRecord->id : null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        // If role is dokter and doctor detail provided, create Doctor record automatically if not existing
        if ($validated['role'] === 'dokter' && !empty($validated['clinic_id'])) {
            Doctor::create([
                'user_id' => $user->id,
                'clinic_id' => $validated['clinic_id'],
                'nip_sip' => $validated['nip_sip'] ?? ('SIP/DOC/' . rand(100, 999)),
                'name' => $validated['name'],
                'specialization' => $validated['specialization'] ?? 'Spesialis Umum',
                'phone' => $validated['phone'] ?? '-',
                'consultation_fee' => $validated['consultation_fee'] ?? 50000,
                'is_active' => true,
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna baru ' . $user->name . ' (' . strtoupper($user->role) . ') berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        $user->load(['userRole', 'doctor.clinic']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['doctor']);

        $rolesList = [
            'admin' => 'Administrator (Akses Penuh)',
            'dokter' => 'Dokter Klinik (Pemeriksaan & Resep)',
            'apoteker' => 'Apoteker / Farmasi (Pengelolaan Obat)',
            'resepsionis' => 'Resepsionis / Front Office (Pendaftaran & Kasir)',
        ];

        $clinics = Clinic::where('is_active', true)->get();

        return view('users.edit', compact('user', 'rolesList', 'clinics'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|in:admin,dokter,apoteker,resepsionis',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'clinic_id' => 'required_if:role,dokter|nullable|exists:clinics,id',
            'nip_sip' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        $roleRecord = Role::where('slug', $validated['role'])->orWhere('name', 'like', "%{$validated['role']}%")->first();

        $userData = [
            'role_id' => $roleRecord ? $roleRecord->id : $user->role_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Update Doctor info if user has a Doctor profile or role changed to doctor
        if ($validated['role'] === 'dokter') {
            if ($user->doctor) {
                $user->doctor->update([
                    'name' => $validated['name'],
                    'clinic_id' => $validated['clinic_id'] ?? $user->doctor->clinic_id,
                    'nip_sip' => $validated['nip_sip'] ?? $user->doctor->nip_sip,
                    'specialization' => $validated['specialization'] ?? $user->doctor->specialization,
                    'phone' => $validated['phone'] ?? $user->doctor->phone,
                    'consultation_fee' => $validated['consultation_fee'] ?? $user->doctor->consultation_fee,
                ]);
            } else if (!empty($validated['clinic_id'])) {
                Doctor::create([
                    'user_id' => $user->id,
                    'clinic_id' => $validated['clinic_id'],
                    'nip_sip' => $validated['nip_sip'] ?? ('SIP/DOC/' . rand(100, 999)),
                    'name' => $validated['name'],
                    'specialization' => $validated['specialization'] ?? 'Spesialis Umum',
                    'phone' => $validated['phone'] ?? '-',
                    'consultation_fee' => $validated['consultation_fee'] ?? 50000,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Data & password akun pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.']);
        }

        $name = $user->name;

        if ($user->doctor) {
            $user->doctor->delete();
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna ' . $name . ' berhasil dihapus dari sistem.');
    }
}
