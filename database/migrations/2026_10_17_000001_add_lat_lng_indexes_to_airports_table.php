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
        Schema::table('airports', function (Blueprint $table) {
            // Add indexes for latitude and longitude to optimize radius geospatial queries
            $table->index('latitude');
            $table->index('longitude');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airports', function (Blueprint $table) {
            $table->dropIndex(['latitude']);
            $table->dropIndex(['longitude']);
            $table->dropIndex(['latitude', 'longitude']);
        });
    }
};
