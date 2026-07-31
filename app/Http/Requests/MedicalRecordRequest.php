<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_id' => 'required|exists:registrations,id',
            'complaints' => 'required|string',
            'medical_history' => 'nullable|string',
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|numeric|between:30,45',
            'height' => 'nullable|numeric|between:30,250',
            'weight' => 'nullable|numeric|between:1,300',
            'diagnosis' => 'required|string',
            'doctor_notes' => 'nullable|string',
            'treatment_ids' => 'nullable|array',
            'treatment_ids.*' => 'exists:treatments,id',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
            'medicines' => 'nullable|array',
        ];
    }
}
