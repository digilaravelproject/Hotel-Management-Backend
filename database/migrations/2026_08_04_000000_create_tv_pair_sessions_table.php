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
        Schema::create('tv_pair_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('pair_code', 12)->unique();
            $table->string('device_id');
            $table->string('mac_address');
            $table->string('ip_address')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('os_version')->nullable();
            $table->enum('status', ['pending', 'paired', 'expired'])->default('pending');
            $table->foreignId('hotel_admin_id')->nullable()->constrained('hotel_admins')->onDelete('cascade');
            $table->string('assigned_room_no')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['pair_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_pair_sessions');
    }
};
