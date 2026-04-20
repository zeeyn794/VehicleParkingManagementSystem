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
        Schema::table('parking_logs', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_slot_id')->constrained()->onDelete('cascade');
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            $table->decimal('total_fee', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_logs', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id', 'parking_slot_id']);
            $table->dropColumn(['vehicle_id', 'parking_slot_id', 'entry_time', 'exit_time', 'total_fee']);
        });
    }
};
