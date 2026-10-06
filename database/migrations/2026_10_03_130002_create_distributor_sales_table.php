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
        Schema::create('distributor_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hotel_id')->constrained('hotel_admins')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status')->default('paid'); // paid, pending, failed
            $table->string('payment_method')->default('direct'); // direct, cash, transfer, wallet
            $table->string('license_key_issued')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['distributor_id', 'hotel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_sales');
    }
};
