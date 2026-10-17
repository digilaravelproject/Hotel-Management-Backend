<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_admin_id')->nullable();
            $table->unsignedBigInteger('distributor_id')->nullable();
            $table->string('gateway_code')->nullable(); // icici, razorpay, payu
            $table->string('transaction_id')->unique(); // Payment gateway reference / order ID
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('INR');
            $table->string('status'); // success, pending, failed, refunded
            $table->text('payload')->nullable(); // Raw response payload
            $table->string('plan_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_refunded')->default(false);
            $table->boolean('idempotency_checked')->default(false); // Prevents double deduction
            $table->timestamps();

            $table->index('hotel_admin_id');
            $table->index('gateway_code');
            $table->index('status');
            $table->foreign('hotel_admin_id')->references('id')->on('hotel_admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
