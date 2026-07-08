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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotel_admins')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile_number');
            $table->string('room_number');
            $table->dateTime('check_in_datetime');
            $table->dateTime('check_out_datetime');
            $table->timestamps();

            // Index for performance optimization when querying active guests per room
            $table->index(['hotel_id', 'room_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
