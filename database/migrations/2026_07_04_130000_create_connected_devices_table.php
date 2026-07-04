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
        Schema::create('connected_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_admin_id')->constrained('hotel_admins')->onDelete('cascade');
            $table->string('room_no');
            $table->string('device_id')->unique();
            $table->string('mac_address');
            $table->string('ip_address')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('os_version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connected_devices');
    }
};
