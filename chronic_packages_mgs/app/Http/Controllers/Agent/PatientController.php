<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;
        
        // Get all unique patients from agent's bookings
        $patientIds = Booking::where('agent_id', $agent->id)
            ->distinct()
            ->pluck('patient_id');
        
        $patients = Patient::with(['user', 'bookings' => function($query) use ($agent) {
            $query->where('agent_id', $agent->id)->latest();
        }])
            ->whereIn('id', $patientIds)
            ->latest()
            ->paginate(15);

        return view('agent.patients.index', compact('patients'));
    }
}
