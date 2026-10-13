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
        Schema::create('distributor_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('distributor_sale_id')->nullable()->constrained('distributor_sales')->onDelete('set null');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_type')->nullable(); // e.g. 'sale', 'withdrawal'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_wallet_transactions');
    }
};
