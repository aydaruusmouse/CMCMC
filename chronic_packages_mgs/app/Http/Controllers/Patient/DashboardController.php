<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Appointment;
use App\Services\HealthDataService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(HealthDataService $healthDataService)
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found. Please contact administrator.');
        }
        
        $activeBooking = Booking::with(['package', 'doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $healthSummary = $healthDataService->getPatientHealthSummary($patient->id);
        
        $upcomingAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        $recentConsultations = $patient->consultations()
            ->with('doctor.user')
            ->latest()
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'activeBooking',
            'healthSummary',
            'upcomingAppointments',
            'recentConsultations'
        ));
    }
}