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
            if (!Schema::hasColumn('hotel_admins', 'distributor_id')) {
                $table->foreignId('distributor_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('users')
                      ->nullOnDelete();
                $table->index('distributor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
        });
    }
};
