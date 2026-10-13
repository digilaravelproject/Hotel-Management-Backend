<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->boolean('auto_renew')->default(false)->after('expiry_date');
            $table->enum('renewal_type', ['auto', 'manual'])->default('manual')->after('auto_renew');
        });
    }

    public function down(): void
    {
        Schema::table('hotel_admins', function (Blueprint $table) {
            $table->dropColumn(['auto_renew', 'renewal_type']);
        });
    }
};
