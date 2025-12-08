<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        $consultations = Consultation::with(['patient.user', 'booking.package', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(15);

        return view('doctor.consultations.index', compact('consultations'));
    }

    public function create(Booking $booking)
    {
        $doctor = Auth::user()->doctor;
        
        // Verify doctor has access to this booking
        if ($booking->doctor_id !== $doctor->id) {
            abort(403, 'You do not have access to this booking.');
        }

        $patient = $booking->patient;
        $healthData = $patient->healthData()
            ->where('booking_id', $booking->id)
            ->latest('recorded_date')
            ->limit(10)
            ->get();

        // Get upcoming appointments for this booking
        $appointments = $booking->appointments()
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->get();

        return view('doctor.consultations.create', compact('booking', 'patient', 'healthData', 'appointments'));
    }

    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'consultation_notes' => 'nullable|string',
            'prescription' => 'nullable|string',
            'medical_recommendations' => 'nullable|string',
            'next_visit_notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
        ]);

        $doctor = Auth::user()->doctor;

        Consultation::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $booking->patient_id,
            'booking_id' => $booking->id,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'consultation_notes' => $validated['consultation_notes'] ?? null,
            'prescription' => $validated['prescription'] ?? null,
            'medical_recommendations' => $validated['medical_recommendations'] ?? null,
            'next_visit_notes' => $validated['next_visit_notes'] ?? null,
            'next_visit_date' => $validated['next_visit_date'] ?? null,
        ]);

        return redirect()->route('doctor.consultations.index')
            ->with('success', 'Consultation notes saved successfully!');
    }
}