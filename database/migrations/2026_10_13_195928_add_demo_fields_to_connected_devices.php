<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connected_devices', function (Blueprint $table) {
            $table->boolean('is_demo')->default(false)->after('brand');
            $table->timestamp('demo_expires_at')->nullable()->after('is_demo');
            $table->foreignId('distributor_id')->nullable()->constrained('users')->nullOnDelete()->after('demo_expires_at');
            $table->enum('demo_status', ['active', 'expired', 'converted'])->default('active')->after('distributor_id');
        });
    }

    public function down(): void
    {
        Schema::table('connected_devices', function (Blueprint $table) {
            $table->dropColumn(['is_demo', 'demo_expires_at', 'distributor_id', 'demo_status']);
        });
    }
};
