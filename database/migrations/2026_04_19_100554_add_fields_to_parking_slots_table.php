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
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->string('slot_number')->unique();
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->string('location')->default('Ground Floor');
            $table->decimal('hourly_rate', 8, 2)->default(2.50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->dropColumn(['slot_number', 'status', 'location', 'hourly_rate']);
        });
    }
};
