<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Patient;
use App\Models\User;
use App\Models\Package;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookingService
{
    public function createBooking(array $data, $agentId): Booking
    {
        return DB::transaction(function () use ($data, $agentId) {
            // Create or get user for patient
            $user = User::firstOrCreate(
                ['email' => $data['patient_email']],
                [
                    'name' => $data['patient_name'],
                    'password' => Hash::make('password123'), // Default password, should be changed
                    'role' => 'patient',
                    'status' => 'active',
                    'phone' => $data['patient_phone'],
                ]
            );

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

            // Create booking
            $booking = Booking::create([
                'agent_id' => $agentId,
                'patient_id' => $patient->id,
                'doctor_id' => $data['doctor_id'],
                'package_id' => $data['package_id'],
                'booking_date' => $data['booking_date'] ?? now(),
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

            return $booking;
        });
    }

    public function updateBookingStatus(Booking $booking, string $status): void
    {
        $booking->update(['status' => $status]);
    }
}
