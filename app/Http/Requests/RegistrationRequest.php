<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'registration_date' => 'required|date',
            'complaints' => 'nullable|string',
            'complaint' => 'nullable|string',
            'visit_type' => 'nullable|string',
        ];
    }
}
