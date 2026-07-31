<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor') ? $this->route('doctor')->id : null;

        return [
            'name' => 'required|string|max:255',
            'nip_sip' => 'required|string|max:50|unique:doctors,nip_sip,' . $doctorId,
            'clinic_id' => 'required|exists:clinics,id',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'consultation_fee' => 'required|numeric|min:0',
            'email' => 'required|email|unique:users,email,' . ($this->route('doctor') ? $this->route('doctor')->user_id : 'NULL'),
            'password' => $doctorId ? 'nullable|string|min:6' : 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
