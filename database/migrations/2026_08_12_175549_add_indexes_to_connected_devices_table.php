
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
        Schema::table('connected_devices', function (Blueprint $table) {
            $table->index(['hotel_admin_id', 'room_no'], 'idx_connected_devices_hotel_room');
            $table->index('device_id', 'idx_connected_devices_device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connected_devices', function (Blueprint $table) {
            $table->dropIndex('idx_connected_devices_hotel_room');
            $table->dropIndex('idx_connected_devices_device_id');
        });
    }
};
