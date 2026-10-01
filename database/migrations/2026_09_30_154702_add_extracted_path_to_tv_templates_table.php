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
            // Stores the relative path inside public/ where the zip was extracted
            // e.g. "themes/theme_1/v1_0_1234567890"
            $table->string('extracted_path')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_templates', function (Blueprint $table) {
            $table->dropColumn('extracted_path');
        });
    }
};
