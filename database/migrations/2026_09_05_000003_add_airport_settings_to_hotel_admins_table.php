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
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->foreignId('primary_airport_id')->nullable()->after('city')->constrained('airports')->nullOnDelete();
            $table->foreignId('secondary_airport_id')->nullable()->after('primary_airport_id')->constrained('airports')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropForeign(['primary_airport_id']);
            $table->dropForeign(['secondary_airport_id']);
            $table->dropColumn(['primary_airport_id', 'secondary_airport_id']);
        });
    }
};
