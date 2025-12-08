<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Services\AppointmentService;
use App\Services\PaymentService;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Package;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected AppointmentService $appointmentService,
        protected PaymentService $paymentService
    ) {}

    public function index()
    {
        $agent = Auth::user()->agent;
        $bookings = Booking::with(['patient.user', 'doctor.user', 'package', 'payments'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->paginate(15);

        return view('agent.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $doctors = Doctor::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->get();
        
        $packages = Package::where('is_active', true)->get();
        $discounts = Discount::where('is_active', true)->get();

        return view('agent.bookings.create', compact('doctors', 'packages', 'discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string',
            'patient_email' => 'required|email',
            'patient_phone' => 'required|string',
            'patient_city' => 'required|string',
            'patient_village' => 'required|string',
            'patient_age' => 'required|integer',
            'doctor_id' => 'required|exists:doctors,id',
            'package_id' => 'required|exists:packages,id',
            'booking_date' => 'required|date',
            'booking_type' => 'required|in:online,in-person',
            'payment_method' => 'required|in:zaad,edahab',
            'source_of_booking' => 'nullable|string',
            'where_heard_from' => 'nullable|string',
            'is_new_patient' => 'nullable|boolean',
            'satisfaction_level' => 'nullable|integer|min:1|max:5',
            'discount_id' => 'nullable|exists:discounts,id',
        ]);

        // Convert checkbox value to boolean
        $validated['is_new_patient'] = $request->has('is_new_patient') ? true : false;

        $agent = Auth::user()->agent;
        
        if (!$agent) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Agent profile not found. Please contact administrator.');
        }
        
        $result = $this->bookingService->createBooking($validated, $agent->id);
        $booking = $result['booking'];

        // Schedule appointments
        $this->appointmentService->scheduleAppointments($booking, 2);

        // If new user was created, show credentials
        if ($result['is_new_user'] && $result['plain_password']) {
            return redirect()->route('agent.bookings.index')
                ->with('success', 'Booking created successfully!')
                ->with('patient_credentials', [
                    'email' => $result['user']->email,
                    'password' => $result['plain_password'],
                    'name' => $result['user']->name,
                ]);
        }

        return redirect()->route('agent.bookings.index')
            ->with('success', 'Booking created successfully! Patient account already exists.');
    }

    public function confirmPayment(Booking $booking, Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $this->paymentService->createPayment($booking, [
            'payment_method' => $booking->payment_method,
            'transaction_id' => $validated['transaction_id'],
        ]);

        return redirect()->back()->with('success', 'Payment confirmed successfully!');
    }
}