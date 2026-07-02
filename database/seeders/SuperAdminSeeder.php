<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;
use App\Models\Plan;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        SuperAdmin::updateOrCreate(
            ['email' => 'admin@hotel.com'],
            ['password' => Hash::make('Admin@123')]
        );

        // Create Default Plans
        $plans = [
            [
                'name' => 'Economy Plan',
                'room_count' => 20,
                'price' => 999.00,
                'status' => true,
                'description' => 'Perfect for small guest houses and boutiques. Standard dashboard and licensing up to 20 rooms/TVs.',
            ],
            [
                'name' => 'Deluxe Plan',
                'room_count' => 50,
                'price' => 1999.00,
                'status' => true,
                'description' => 'Best for mid-sized hotels. Suggested automatically for hotels with 50 rooms. Licensing up to 50 rooms/TVs.',
            ],
            [
                'name' => 'Executive Plan',
                'room_count' => 100,
                'price' => 3999.00,
                'status' => true,
                'description' => 'Ideal for large hotels and premium resorts. Advanced dashboard, high priority support, up to 100 rooms/TVs.',
            ],
            [
                'name' => 'Presidential Plan',
                'room_count' => 250,
                'price' => 7999.00,
                'status' => true,
                'description' => 'Ultimate package for massive chains and grand hotels. Custom layout features, priority API access, up to 250 rooms/TVs.',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                [
                    'room_count' => $plan['room_count'],
                    'price' => $plan['price'],
                    'status' => $plan['status'],
                    'description' => $plan['description'],
                ]
            );
        }
    }
}
