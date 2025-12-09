<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Patient;
use App\Models\User;
use App\Models\Package;
use App\Models\Discount;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingService
{
    public function createBooking(array $data, $agentId): array
    {
        return DB::transaction(function () use ($data, $agentId) {
            $isNewUser = false;
            $plainPassword = null;

            // Check if user exists by phone
            $user = User::where('phone', $data['patient_phone'])->first();

            if (!$user) {
                // Generate secure random password (8 characters: letters and numbers)
                $plainPassword = Str::random(8);
                $isNewUser = true;

                // Create new user for patient
                $user = User::create([
                    'name' => $data['patient_name'],
                    'password' => Hash::make($plainPassword),
                    'role' => 'patient',
                    'status' => 'active',
                    'phone' => $data['patient_phone'],
                ]);
            } else {
                // Update user info if needed
                $user->update([
                    'name' => $data['patient_name'],
                ]);
            }

            // Create or get patient
            $patient = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $data['patient_phone'],
                    'city' => $data['patient_city'],
                    'village' => $data['patient_village'],
                    'age' => $data['patient_age'],
                ]
            );

            // Update patient info if it already existed
            if ($patient->wasRecentlyCreated === false) {
                $patient->update([
                    'phone' => $data['patient_phone'],
                    'city' => $data['patient_city'],
                    'village' => $data['patient_village'],
                    'age' => $data['patient_age'],
                ]);
            }

            // Calculate price with discount
            $package = Package::findOrFail($data['package_id']);
            $finalPrice = $package->price;

            if (!empty($data['discount_id'])) {
                $discount = Discount::findOrFail($data['discount_id']);
                if ($discount->isApplicable()) {
                    if ($discount->type === 'percentage') {
                        $finalPrice = $finalPrice * (1 - $discount->value / 100);
                    } else {
                        $finalPrice = max(0, $finalPrice - $discount->value);
                    }
                    $discount->increment('used_count');
                }
            }

            // Calculate expiration date (1 month from booking date)
            $bookingDate = $data['booking_date'] ?? now();
            $expirationDate = is_string($bookingDate) ? \Carbon\Carbon::parse($bookingDate)->addMonth() : $bookingDate->copy()->addMonth();

            // Create booking
            $booking = Booking::create([
                'agent_id' => $agentId,
                'patient_id' => $patient->id,
                'doctor_id' => $data['doctor_id'],
                'package_id' => $data['package_id'],
                'booking_date' => $bookingDate,
                'expiration_date' => $expirationDate,
                'status' => $data['status'] ?? 'pending',
                'booking_type' => $data['booking_type'] ?? 'in-person',
                'payment_method' => $data['payment_method'] ?? null,
                'source_of_booking' => $data['source_of_booking'] ?? null,
                'where_heard_from' => $data['where_heard_from'] ?? null,
                'is_new_patient' => $data['is_new_patient'] ?? true,
                'satisfaction_level' => $data['satisfaction_level'] ?? null,
                'discount_id' => $data['discount_id'] ?? null,
                'final_price' => $finalPrice,
            ]);

            // Send SMS notification to doctor about new booking
            try {
                $smsService = new SmsService();
                $smsService->sendBookingNotification($booking);
            } catch (\Exception $e) {
                // Log error but don't fail the booking creation
                \Log::error('Failed to send booking SMS notification', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }

            return [
                'booking' => $booking,
                'user' => $user,
                'plain_password' => $plainPassword,
                'is_new_user' => $isNewUser,
                'phone' => $user->phone,
            ];
        });
    }

    public function updateBookingStatus(Booking $booking, string $status): void
    {
        $booking->update(['status' => $status]);
    }
}
