<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Get all appointments (past and upcoming)
        $appointments = Appointment::with(['doctor.user', 'booking.package'])
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        // Separate upcoming and past appointments
        $upcomingAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->get();

        $pastAppointments = Appointment::with(['doctor.user', 'consultations'])
            ->where('patient_id', $patient->id)
            ->where(function($query) {
                $query->where('appointment_date', '<', now())
                      ->orWhere('status', 'completed')
                      ->orWhere('status', 'cancelled');
            })
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.appointments.index', compact('appointments', 'upcomingAppointments', 'pastAppointments'));
    }
}
