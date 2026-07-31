<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'patient_id',
        'doctor_id',
        'clinic_id',
        'schedule_id',
        'queue_number',
        'registration_date',
        'status',
        'complaints',
        'visit_type',
    ];

    public function getComplaintAttribute()
    {
        return $this->attributes['complaints'] ?? null;
    }

    public function getVisitTypeLabelAttribute()
    {
        $type = !empty($this->visit_type) ? strtolower($this->visit_type) : 'umum';
        return match($type) {
            'bpjs' => 'BPJS Kesehatan',
            'asuransi' => 'Asuransi Swasta',
            default => 'Umum (Mandiri)',
        };
    }

    public function getVisitTypeBadgeAttribute()
    {
        $type = !empty($this->visit_type) ? strtolower($this->visit_type) : 'umum';
        return match($type) {
            'bpjs' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'asuransi' => 'bg-purple-100 text-purple-800 border border-purple-200',
            default => 'bg-sky-100 text-sky-800 border border-sky-200',
        };
    }

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
