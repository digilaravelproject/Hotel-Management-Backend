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
            $table->string('hotel_logo')->nullable()->after('hotel_location');
            $table->string('hotel_image')->nullable()->after('hotel_logo');
            $table->string('otp_code')->nullable()->after('status');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropColumn(['hotel_logo', 'hotel_image', 'otp_code', 'otp_expires_at']);
        });
    }
};
