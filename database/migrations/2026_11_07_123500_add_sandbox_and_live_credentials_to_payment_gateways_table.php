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
        Schema::table('payment_gateways', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_gateways', 'sandbox_key')) {
                $table->text('sandbox_key')->nullable()->after('api_secret');
                $table->text('sandbox_secret')->nullable()->after('sandbox_key');
                $table->string('sandbox_merchant_id')->nullable()->after('sandbox_secret');
                $table->text('live_key')->nullable()->after('sandbox_merchant_id');
                $table->text('live_secret')->nullable()->after('live_key');
                $table->string('live_merchant_id')->nullable()->after('live_secret');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $columns = ['sandbox_key', 'sandbox_secret', 'sandbox_merchant_id', 'live_key', 'live_secret', 'live_merchant_id'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('payment_gateways', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
