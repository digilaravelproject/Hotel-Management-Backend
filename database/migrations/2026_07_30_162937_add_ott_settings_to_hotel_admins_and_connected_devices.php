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
            $table->json('global_ott_settings')->nullable()->after('expiry_date');
        });

        Schema::table('connected_devices', function (Blueprint $table) {
            $table->json('ott_overrides')->nullable()->after('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropColumn('global_ott_settings');
        });

        Schema::table('connected_devices', function (Blueprint $table) {
            $table->dropColumn('ott_overrides');
        });
    }
};
