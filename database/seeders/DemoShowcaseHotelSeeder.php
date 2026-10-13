<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelAdmin;
use App\Models\Plan;

class DemoShowcaseHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plan = Plan::orderBy('room_count', 'desc')->first();

        HotelAdmin::updateOrCreate(
            ['email' => 'demo.showcase@digihotel.com'],
            [
                'owner_name' => 'Demo Showcase Manager',
                'hotel_name' => 'DigiLounge Demo Grand Hotel',
                'hotel_location' => 'Cyber City, 5-Star Showcase Suite, Delhi NCR',
                'city' => 'New Delhi',
                'phone' => '+91 9999999999',
                'password' => bcrypt('12345678'),
                'room_count' => 9999,
                'plan_id' => $plan ? $plan->id : null,
                'payment_status' => 'paid',
                'approval_status' => 'approved',
                'status' => true,
                'license_key' => 'DEMO-MASTER-SHOWCASE',
                'description' => 'Official 5-Star Demo Showcase Hotel for TV trials and prospective hotel clients.',
            ]
        );
    }
}
