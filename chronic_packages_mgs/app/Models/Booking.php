<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'agent_id',
        'patient_id',
        'doctor_id',
        'package_id',
        'booking_date',
        'expiration_date',
        'status',
        'booking_type',
        'payment_method',
        'source_of_booking',
        'where_heard_from',
        'is_new_patient',
        'complain',
        'discount_id',
        'final_price',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'expiration_date' => 'date',
        'is_new_patient' => 'boolean',
        'final_price' => 'decimal:2',
    ];

    // Check if booking is expired
    public function isExpired(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    // Check if booking is active (not expired and status is active)
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function healthData(): HasMany
    {
        return $this->hasMany(HealthData::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
