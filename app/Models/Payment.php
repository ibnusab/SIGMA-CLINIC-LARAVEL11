<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'registration_id',
        'patient_id',
        'consultation_fee',
        'treatment_fee',
        'medicine_fee',
        'discount',
        'tax',
        'total_amount',
        'payment_method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'treatment_fee' => 'decimal:2',
        'medicine_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
