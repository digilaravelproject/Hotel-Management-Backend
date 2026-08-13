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
        Schema::create('room_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_admin_id')->constrained('hotel_admins')->onDelete('cascade');
            $table->integer('sr_no')->default(1);
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['hotel_admin_id', 'status', 'sr_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_infos');
    }
};

