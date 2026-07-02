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
        Schema::create('hotel_admins', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('hotel_name');
            $table->string('hotel_location');
            $table->integer('room_count');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('license_key')->nullable();
            $table->string('approval_status')->default('pending'); // pending, approved, disapproved
            $table->boolean('status')->default(true); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_admins');
    }
};
