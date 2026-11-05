<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Delete any unverified premature invoices for Hotel 2 generated today without gateway transaction
        DB::table('invoices')
            ->where('hotel_admin_id', 2)
            ->where('created_at', '>=', '2026-11-05 18:00:00')
            ->delete();

        // 2. Revert Hotel 2 to expired state with original 10 rooms and expired date
        DB::table('hotel_admins')
            ->where('id', 2)
            ->update([
                'payment_status' => 'expired',
                'expiry_date' => '2026-11-04 00:00:00',
                'room_count' => 10,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

