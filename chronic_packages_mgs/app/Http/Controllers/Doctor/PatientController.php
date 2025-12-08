<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        // Get all unique patients from doctor's bookings
        $patientIds = Booking::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id');
        
        $patients = Patient::with(['user', 'bookings' => function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)->latest();
        }, 'healthData' => function($query) {
            $query->latest()->limit(1);
        }])
            ->whereIn('id', $patientIds)
            ->latest()
            ->paginate(15);

        return view('doctor.patients.index', compact('patients'));
    }
}
