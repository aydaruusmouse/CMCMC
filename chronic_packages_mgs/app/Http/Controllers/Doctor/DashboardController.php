<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Appointment;
use App\Services\HealthDataService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(HealthDataService $healthDataService)
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('login')->with('error', 'Doctor profile not found. Please contact administrator.');
        }
        
        $stats = [
            'active_patients' => Booking::where('doctor_id', $doctor->id)
                ->where('status', 'active')
                ->distinct('patient_id')
                ->count('patient_id'),
            'upcoming_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'scheduled')
                ->where('appointment_date', '>=', now())
                ->count(),
            'pending_consultations' => Booking::where('doctor_id', $doctor->id)
                ->where('status', 'active')
                ->whereDoesntHave('consultations')
                ->count(),
        ];

        $criticalAlerts = $healthDataService->getCriticalAlerts();
        $upcomingAppointments = Appointment::with(['patient.user', 'booking'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->limit(10)
            ->get();

        return view('doctor.dashboard', compact('stats', 'criticalAlerts', 'upcomingAppointments'));
    }
}