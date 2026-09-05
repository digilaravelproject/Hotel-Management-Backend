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
        Schema::table('tv_templates', function (Blueprint $table) {
            $table->unsignedInteger('theme_id')->default(1)->after('id')->index();
            $table->string('theme_name')->nullable()->after('theme_id');
            $table->string('preview_image')->nullable()->after('file_path');
            
            // Drop global unique constraint on version if exists, and make composite unique
            $table->dropUnique(['version']);
            $table->unique(['theme_id', 'version']);
        });

        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->unsignedInteger('selected_theme_id')->default(1)->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropColumn('selected_theme_id');
        });

        Schema::table('tv_templates', function (Blueprint $table) {
            $table->dropUnique(['theme_id', 'version']);
            $table->unique('version');
            $table->dropColumn(['theme_id', 'theme_name', 'preview_image']);
        });
    }
};
