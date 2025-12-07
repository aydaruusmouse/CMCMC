<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Str;

class PaymentService
{
    public function createPayment(Booking $booking, array $data): Payment
    {
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'patient_id' => $booking->patient_id,
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'amount' => $booking->final_price,
            'status' => 'completed',
            'paid_at' => now(),
            'receipt_number' => $this->generateReceiptNumber(),
        ]);

        // Update booking status
        $booking->update(['status' => 'active']);

        return $payment;
    }

    public function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'RCP-' . strtoupper(Str::random(8));
        } while (Payment::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }

    public function markPaymentAsCompleted(Payment $payment, string $transactionId = null): void
    {
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $transactionId ?? $payment->transaction_id,
            'paid_at' => now(),
        ]);

        $payment->booking->update(['status' => 'active']);
    }
}
