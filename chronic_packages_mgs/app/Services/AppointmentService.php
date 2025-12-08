<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Booking;
use Carbon\Carbon;

class AppointmentService
{
    public function scheduleAppointments(Booking $booking, int $count = 2): void
    {
        $startDate = Carbon::parse($booking->booking_date);
        
        for ($i = 0; $i < $count; $i++) {
            $appointmentDate = $startDate->copy()->addMonths($i)->startOfMonth();
            
            // Schedule for the 1st and 15th of each month
            if ($i % 2 == 0) {
                $appointmentDate->day(1);
            } else {
                $appointmentDate->day(15);
            }

            Appointment::create([
                'patient_id' => $booking->patient_id,
                'doctor_id' => $booking->doctor_id,
                'booking_id' => $booking->id,
                'appointment_date' => $appointmentDate,
                'status' => 'scheduled',
            ]);
        }
    }

    public function createAppointment(Booking $booking, Carbon $date): Appointment
    {
        return Appointment::create([
            'patient_id' => $booking->patient_id,
            'doctor_id' => $booking->doctor_id,
            'booking_id' => $booking->id,
            'appointment_date' => $date,
            'status' => 'scheduled',
        ]);
    }

    public function getUpcomingAppointments($patientId = null, $doctorId = null, $days = 7)
    {
        $query = Appointment::where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->where('appointment_date', '<=', now()->addDays($days));

        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->orderBy('appointment_date')->get();
    }
}

