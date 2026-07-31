<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_number',
        'registration_id',
        'patient_id',
        'doctor_id',
        'examination_date',
        'complaints',
        'medical_history',
        'blood_pressure',
        'temperature',
        'height',
        'weight',
        'bmi',
        'diagnosis',
        'doctor_notes',
    ];

    protected $casts = [
        'examination_date' => 'datetime',
        'temperature' => 'decimal:1',
        'height' => 'decimal:1',
        'weight' => 'decimal:1',
        'bmi' => 'decimal:1',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'medical_record_treatment')
                    ->withPivot('price', 'notes')
                    ->withTimestamps();
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
