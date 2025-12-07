<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->date('booking_date');
            $table->enum('status', ['active', 'pending', 'completed'])->default('pending');
            $table->enum('booking_type', ['online', 'in-person'])->default('in-person');
            $table->enum('payment_method', ['zaad', 'edahab'])->nullable();
            $table->string('source_of_booking')->nullable();
            $table->string('where_heard_from')->nullable();
            $table->boolean('is_new_patient')->default(true);
            $table->integer('satisfaction_level')->nullable(); // 1-5
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null');
            $table->decimal('final_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
