<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Package;
use App\Models\Doctor;
use App\Models\Agent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Overall Statistics
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::where('status', 'active')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [Carbon::parse($dateFrom), Carbon::parse($dateTo)->endOfDay()])
                ->sum('amount'),
        ];

        // Package Sales
        $packageSales = Package::withCount(['bookings' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('created_at', [Carbon::parse($dateFrom), Carbon::parse($dateTo)->endOfDay()]);
        }])->get();

        // Doctor Performance
        $doctorPerformance = Doctor::with('user')
            ->withCount(['bookings' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [Carbon::parse($dateFrom), Carbon::parse($dateTo)->endOfDay()]);
            }])
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        // Agent Performance
        $agentPerformance = Agent::with('user')
            ->withCount(['bookings' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [Carbon::parse($dateFrom), Carbon::parse($dateTo)->endOfDay()]);
            }])
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with(['patient.user', 'booking.package'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [Carbon::parse($dateFrom), Carbon::parse($dateTo)->endOfDay()])
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.reports.index', compact(
            'stats',
            'packageSales',
            'doctorPerformance',
            'agentPerformance',
            'recentPayments',
            'dateFrom',
            'dateTo'
        ));
    }
}
