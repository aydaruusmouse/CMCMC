<?php

namespace App\Services;

use App\Models\HealthData;
use App\Models\Booking;

class HealthDataService
{
    public function createHealthData(Booking $booking, array $data): HealthData
    {
        return HealthData::create([
            'patient_id' => $booking->patient_id,
            'booking_id' => $booking->id,
            'recorded_date' => $data['recorded_date'] ?? now(),
            'blood_sugar' => $data['blood_sugar'] ?? null,
            'insulin_intake' => $data['insulin_intake'] ?? null,
            'diet_log' => $data['diet_log'] ?? null,
            'systolic_bp' => $data['systolic_bp'] ?? null,
            'diastolic_bp' => $data['diastolic_bp'] ?? null,
            'bp_symptoms' => $data['bp_symptoms'] ?? null,
            'weight' => $data['weight'] ?? null,
            'maternal_symptoms' => $data['maternal_symptoms'] ?? null,
            'fetal_movements' => $data['fetal_movements'] ?? null,
            'temperature' => $data['temperature'] ?? null,
            'pediatric_symptoms' => $data['pediatric_symptoms'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function getPatientHealthSummary($patientId, $days = 30)
    {
        $healthData = HealthData::where('patient_id', $patientId)
            ->where('recorded_date', '>=', now()->subDays($days))
            ->orderBy('recorded_date', 'desc')
            ->get();

        return [
            'latest' => $healthData->first(),
            'average_blood_sugar' => $healthData->whereNotNull('blood_sugar')->avg('blood_sugar'),
            'average_systolic_bp' => $healthData->whereNotNull('systolic_bp')->avg('systolic_bp'),
            'average_diastolic_bp' => $healthData->whereNotNull('diastolic_bp')->avg('diastolic_bp'),
            'average_weight' => $healthData->whereNotNull('weight')->avg('weight'),
            'total_records' => $healthData->count(),
        ];
    }

    public function getCriticalAlerts($patientId = null)
    {
        $query = HealthData::where(function ($q) {
            $q->where('blood_sugar', '>', 200)
              ->orWhere('blood_sugar', '<', 70)
              ->orWhere('systolic_bp', '>', 140)
              ->orWhere('diastolic_bp', '>', 90)
              ->orWhere('systolic_bp', '<', 90)
              ->orWhere('diastolic_bp', '<', 60);
        })->where('recorded_date', '>=', now()->subDays(7));

        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        return $query->with('patient.user')->orderBy('recorded_date', 'desc')->get();
    }
}

