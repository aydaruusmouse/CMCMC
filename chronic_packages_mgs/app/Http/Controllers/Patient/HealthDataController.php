<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Services\HealthDataService;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthDataController extends Controller
{
    public function __construct(protected HealthDataService $healthDataService) {}

    public function index()
    {
        $patient = Auth::user()->patient;
        $activeBooking = Booking::where('patient_id', $patient->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$activeBooking) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'No active booking found.');
        }

        $healthData = $patient->healthData()
            ->where('booking_id', $activeBooking->id)
            ->latest('recorded_date')
            ->paginate(15);

        return view('patient.health-data.index', compact('healthData', 'activeBooking'));
    }

    public function create()
    {
        $patient = Auth::user()->patient;
        $activeBooking = Booking::with('package')
            ->where('patient_id', $patient->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$activeBooking) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'No active booking found.');
        }

        return view('patient.health-data.create', compact('activeBooking'));
    }

    public function store(Request $request)
    {
        $patient = Auth::user()->patient;
        $activeBooking = Booking::where('patient_id', $patient->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$activeBooking) {
            return redirect()->back()->with('error', 'No active booking found.');
        }

        $validated = $request->validate([
            'recorded_date' => 'required|date',
            'blood_sugar' => 'nullable|numeric',
            'insulin_intake' => 'nullable|string',
            'diet_log' => 'nullable|string',
            'systolic_bp' => 'nullable|integer',
            'diastolic_bp' => 'nullable|integer',
            'bp_symptoms' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'maternal_symptoms' => 'nullable|string',
            'fetal_movements' => 'nullable|integer',
            'temperature' => 'nullable|numeric',
            'pediatric_symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->healthDataService->createHealthData($activeBooking, $validated);

        return redirect()->route('patient.health-data.index')
            ->with('success', 'Health data recorded successfully!');
    }
}