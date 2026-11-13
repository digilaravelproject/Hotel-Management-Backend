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
        Schema::table('app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('app_settings', 'turnstile_enabled')) {
                $table->boolean('turnstile_enabled')->default(false)->after('default_theme_id');
            }
            if (!Schema::hasColumn('app_settings', 'turnstile_site_key')) {
                $table->string('turnstile_site_key', 150)->nullable()->after('turnstile_enabled');
            }
            if (!Schema::hasColumn('app_settings', 'turnstile_secret_key')) {
                $table->text('turnstile_secret_key')->nullable()->after('turnstile_site_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('app_settings', 'turnstile_enabled')) {
                $columnsToDrop[] = 'turnstile_enabled';
            }
            if (Schema::hasColumn('app_settings', 'turnstile_site_key')) {
                $columnsToDrop[] = 'turnstile_site_key';
            }
            if (Schema::hasColumn('app_settings', 'turnstile_secret_key')) {
                $columnsToDrop[] = 'turnstile_secret_key';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
