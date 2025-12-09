<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\SmsService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders to doctors for upcoming appointments (24 hours before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming appointments...');

        // Get appointments that are 24 hours from now (within next 25 hours to catch all)
        $startTime = Carbon::now()->addHours(23);
        $endTime = Carbon::now()->addHours(25);

        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->where('status', 'scheduled')
            ->whereBetween('appointment_date', [$startTime, $endTime])
            ->where('reminder_sent', false)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments need reminders at this time.');
            return 0;
        }

        $smsService = new SmsService();
        $sentCount = 0;
        $failedCount = 0;

        foreach ($appointments as $appointment) {
            $this->info("Sending reminder for appointment ID: {$appointment->id}");

            if ($smsService->sendAppointmentReminder($appointment)) {
                $appointment->update(['reminder_sent' => true]);
                $sentCount++;
                $this->info("✓ Reminder sent successfully");
            } else {
                $failedCount++;
                $this->error("✗ Failed to send reminder");
            }
        }

        $this->info("Completed: {$sentCount} sent, {$failedCount} failed");
        return 0;
    }
}
