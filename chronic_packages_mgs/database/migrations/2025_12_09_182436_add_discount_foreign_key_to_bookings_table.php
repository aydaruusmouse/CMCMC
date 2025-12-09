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
        // Only add foreign key if discounts table exists
        if (Schema::hasTable('discounts')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('discount_id')
                    ->references('id')
                    ->on('discounts')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
        });
    }
};
