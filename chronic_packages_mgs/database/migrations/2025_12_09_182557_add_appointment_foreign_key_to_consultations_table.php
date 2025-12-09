<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add foreign key if appointments table exists
        if (Schema::hasTable('appointments')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->foreign('appointment_id')
                    ->references('id')
                    ->on('appointments')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
        });
    }
};
