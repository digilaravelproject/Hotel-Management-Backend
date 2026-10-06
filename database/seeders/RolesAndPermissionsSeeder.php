<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular Permissions List
        $permissions = [
            // User & Role Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.manage',

            // Hotel Management
            'hotels.view',
            'hotels.create',
            'hotels.edit',
            'hotels.delete',
            'hotels.approve',
            'hotels.toggle_status',

            // Plans & Packages
            'plans.view',
            'plans.manage',
            'packages.sell',

            // Devices & OTA
            'devices.view',
            'devices.manage',
            'templates.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 2. Define the 3 Primary Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $distributorRole = Role::firstOrCreate(['name' => 'distributor', 'guard_name' => 'web']);
        $hotelAdminRole = Role::firstOrCreate(['name' => 'hotel_admin', 'guard_name' => 'web']);

        // Assign Permissions to Roles
        // Super Admin gets all permissions
        $superAdminRole->syncPermissions(Permission::all());

        // Distributor gets hotel onboarding and package selling capabilities
        $distributorRole->syncPermissions([
            'hotels.view',
            'hotels.create',
            'hotels.edit',
            'plans.view',
            'packages.sell',
        ]);

        // Hotel Admin gets room/TV device and local settings permissions
        $hotelAdminRole->syncPermissions([
            'hotels.view',
            'devices.view',
            'devices.manage',
        ]);

        // 3. Create or Seed Sample Users for Verification
        // Default Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@hotel.com'],
            [
                'name' => 'System Super Admin',
                'phone' => '9876543210',
                'password' => Hash::make('password123'),
                'status' => true,
            ]
        );
        $superAdminUser->assignRole($superAdminRole);

        // Default Distributor User (MVP Partner)
        $distributorUser = User::firstOrCreate(
            ['email' => 'distributor@hotel.com'],
            [
                'name' => 'Apex Hospitality Distributors',
                'phone' => '9876543211',
                'password' => Hash::make('password123'),
                'status' => true,
            ]
        );
        $distributorUser->assignRole($distributorRole);

        // 4. Seed Standard Subscription Plans if none exist
        if (\App\Models\Plan::count() === 0) {
            \App\Models\Plan::create([
                'name' => 'Silver Hospitality (Up to 25 Rooms)',
                'room_count' => 25,
                'price' => 1499.00,
                'status' => true,
                'description' => 'Essential Smart TV dashboard and live channel streaming package for boutique hotels.',
                'ott_platforms' => ['YouTube', 'Netflix'],
            ]);

            \App\Models\Plan::create([
                'name' => 'Gold Executive (Up to 50 Rooms)',
                'room_count' => 50,
                'price' => 2999.00,
                'status' => true,
                'description' => 'Comprehensive smart hotel entertainment with OTT apps and flight updates.',
                'ott_platforms' => ['YouTube', 'Netflix', 'Prime Video', 'Disney+ Hotstar'],
            ]);

            \App\Models\Plan::create([
                'name' => 'Platinum Luxury (Up to 100 Rooms)',
                'room_count' => 100,
                'price' => 4999.00,
                'status' => true,
                'description' => 'Flagship package for luxury resorts with full catalog of OTT applications and premium templates.',
                'ott_platforms' => ['YouTube', 'Netflix', 'Prime Video', 'Disney+ Hotstar', 'Zee5', 'SonyLIV'],
            ]);
        }
    }
}
