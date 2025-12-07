<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->date('recorded_date');
            
            // Chronic - Diabetes fields
            $table->decimal('blood_sugar', 5, 2)->nullable();
            $table->string('insulin_intake')->nullable();
            $table->text('diet_log')->nullable();
            
            // Chronic - Hypertension fields
            $table->integer('systolic_bp')->nullable();
            $table->integer('diastolic_bp')->nullable();
            $table->text('bp_symptoms')->nullable();
            
            // Maternal fields
            $table->decimal('weight', 5, 2)->nullable();
            $table->text('maternal_symptoms')->nullable();
            $table->integer('fetal_movements')->nullable();
            
            // Pediatric fields
            $table->decimal('temperature', 4, 2)->nullable();
            $table->text('pediatric_symptoms')->nullable();
            
            // General fields
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_data');
    }
};
