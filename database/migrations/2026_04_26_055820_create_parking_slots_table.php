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
        Schema::create('parking_slots', function (Blueprint $table) {
        $table->id();
        $table->string('slot_number');
        $table->string('slot_code')->nullable(); 
        $table->string('status')->default('available');
        $table->string('type')->nullable();
        $table->decimal('hourly_rate', 8, 2)->default(50.00);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
