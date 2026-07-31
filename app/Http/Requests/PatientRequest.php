<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient') ? $this->route('patient')->id : null;

        return [
            'nik' => 'required|string|size:16|unique:patients,nik,' . $patientId,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'blood_type' => 'nullable|string|in:A,B,AB,O',
            'allergies' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'name.required' => 'Nama lengkap pasien wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon/HP wajib diisi.',
        ];
    }
}
