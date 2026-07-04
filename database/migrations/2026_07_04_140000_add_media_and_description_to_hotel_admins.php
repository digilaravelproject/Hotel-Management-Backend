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
            $table->text('description')->nullable()->after('hotel_location');
            $table->json('slider_images')->nullable()->after('hotel_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropColumn(['description', 'slider_images']);
        });
    }
};
