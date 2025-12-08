<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\BookingController as AgentBookingController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ConsultationController as DoctorConsultationController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\HealthDataController as PatientHealthDataController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
        
        // Package Management
        Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
        
        // Discount Management
        Route::resource('discounts', \App\Http\Controllers\Admin\DiscountController::class);
        
        // Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    });

    // Agent Routes
    Route::prefix('agent')->name('agent.')->middleware('role:agent')->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', AgentBookingController::class);
        Route::post('/bookings/{booking}/confirm-payment', [AgentBookingController::class, 'confirmPayment'])->name('bookings.confirm-payment');
        Route::get('/patients', [\App\Http\Controllers\Agent\PatientController::class, 'index'])->name('patients.index');
    });

    // Doctor Routes
    Route::prefix('doctor')->name('doctor.')->middleware('role:doctor')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/patients', [\App\Http\Controllers\Doctor\PatientController::class, 'index'])->name('patients.index');
        Route::get('/consultations', [DoctorConsultationController::class, 'index'])->name('consultations.index');
        Route::get('/consultations/create/{booking}', [DoctorConsultationController::class, 'create'])->name('consultations.create');
        Route::post('/consultations/{booking}', [DoctorConsultationController::class, 'store'])->name('consultations.store');
    });

    // Patient Routes
    Route::prefix('patient')->name('patient.')->middleware('role:patient')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/health-data', [PatientHealthDataController::class, 'index'])->name('health-data.index');
        Route::get('/health-data/create', [PatientHealthDataController::class, 'create'])->name('health-data.create');
        Route::post('/health-data', [PatientHealthDataController::class, 'store'])->name('health-data.store');
        Route::get('/appointments', [\App\Http\Controllers\Patient\AppointmentController::class, 'index'])->name('appointments.index');
    });
});