<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;
        
        if (!$agent) {
            return redirect()->route('login')->with('error', 'Agent profile not found. Please contact administrator.');
        }
        
        $stats = [
            'total_bookings' => Booking::where('agent_id', $agent->id)->count(),
            'active_bookings' => Booking::where('agent_id', $agent->id)->where('status', 'active')->count(),
            'pending_bookings' => Booking::where('agent_id', $agent->id)->where('status', 'pending')->count(),
            'total_revenue' => Payment::whereHas('booking', function ($q) use ($agent) {
                $q->where('agent_id', $agent->id);
            })->where('status', 'completed')->sum('amount'),
        ];

        $recentBookings = Booking::with(['patient.user', 'doctor.user', 'package'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('agent.dashboard', compact('stats', 'recentBookings'));
    }
}