<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $apiUrl = 'http://172.16.53.106:8082/sdf/web/sms/otp/send';
    private string $username = 'sms_api';
    private string $password = 'SMS_API@123';
    private string $fromNumber = 'Shaafi CMCMC';

    /**
     * Send SMS notification
     *
     * @param string $toNumber Phone number in format +252XXXXXXXXX
     * @param string $message Message content
     * @return bool
     */
    public function sendSms(string $toNumber, string $message): bool
    {
        try {
            // Ensure phone number has country code
            if (!str_starts_with($toNumber, '+')) {
                // If starts with 0, replace with +252
                if (str_starts_with($toNumber, '0')) {
                    $toNumber = '+252' . substr($toNumber, 1);
                } elseif (str_starts_with($toNumber, '252')) {
                    $toNumber = '+' . $toNumber;
                } else {
                    $toNumber = '+252' . $toNumber;
                }
            }

            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->apiUrl, [
                    'fromNumber' => $this->fromNumber,
                    'toNumber' => $toNumber,
                    'message' => $message,
                    'timeToLiveSeconds' => 240,
                    'application' => 'Telesom_CRM'
                ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'to' => $toNumber,
                    'message' => $message
                ]);
                return true;
            } else {
                Log::error('SMS sending failed', [
                    'to' => $toNumber,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS service exception', [
                'to' => $toNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send appointment reminder to doctor
     *
     * @param \App\Models\Appointment $appointment
     * @return bool
     */
    public function sendAppointmentReminder($appointment): bool
    {
        $doctor = $appointment->doctor;
        $patient = $appointment->patient;
        
        if (!$doctor || !$doctor->user || !$doctor->user->phone) {
            Log::warning('Cannot send appointment reminder: Doctor phone not found', [
                'appointment_id' => $appointment->id
            ]);
            return false;
        }

        $appointmentDate = $appointment->appointment_date->format('M d, Y h:i A');
        $message = "Appointment Reminder: You have an appointment with {$patient->user->name} on {$appointmentDate}. Please be prepared.";

        return $this->sendSms($doctor->user->phone, $message);
    }

    /**
     * Send booking notification to doctor
     *
     * @param \App\Models\Booking $booking
     * @return bool
     */
    public function sendBookingNotification($booking): bool
    {
        $doctor = $booking->doctor;
        $patient = $booking->patient;
        
        if (!$doctor || !$doctor->user || !$doctor->user->phone) {
            Log::warning('Cannot send booking notification: Doctor phone not found', [
                'booking_id' => $booking->id
            ]);
            return false;
        }

        $bookingDate = $booking->booking_date->format('M d, Y');
        $message = "New Booking: {$patient->user->name} has been assigned to you. Booking date: {$bookingDate}. Package: {$booking->package->name}.";

        return $this->sendSms($doctor->user->phone, $message);
    }
}

