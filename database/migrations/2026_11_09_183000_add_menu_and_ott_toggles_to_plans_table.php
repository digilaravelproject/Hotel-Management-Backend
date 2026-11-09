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
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'is_ott_enabled')) {
                $table->boolean('is_ott_enabled')->default(true)->after('ott_platforms');
            }
            if (!Schema::hasColumn('plans', 'allowed_menus')) {
                $table->json('allowed_menus')->nullable()->after('is_ott_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'allowed_menus')) {
                $table->dropColumn('allowed_menus');
            }
            if (Schema::hasColumn('plans', 'is_ott_enabled')) {
                $table->dropColumn('is_ott_enabled');
            }
        });
    }
};

