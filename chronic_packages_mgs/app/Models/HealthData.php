<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthData extends Model
{
    protected $fillable = [
        'patient_id',
        'booking_id',
        'recorded_date',
        'blood_sugar',
        'insulin_intake',
        'diet_log',
        'systolic_bp',
        'diastolic_bp',
        'bp_symptoms',
        'weight',
        'maternal_symptoms',
        'fetal_movements',
        'temperature',
        'pediatric_symptoms',
        'notes',
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'blood_sugar' => 'decimal:2',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
