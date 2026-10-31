<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\ConnectedDevice;
use App\Models\DistributorSale;
use Carbon\Carbon;

class RichDemoPlatformSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch or ensure Subscription Plans
        $plans = Plan::all();
        if ($plans->isEmpty()) {
            $plans = collect([
                Plan::create(['name' => 'Economy Plan', 'price' => 999, 'room_count' => 50, 'status' => true]),
                Plan::create(['name' => 'Deluxe Plan', 'price' => 1999, 'room_count' => 100, 'status' => true]),
                Plan::create(['name' => 'Executive Plan', 'price' => 3999, 'room_count' => 180, 'status' => true]),
                Plan::create(['name' => 'Presidential Plan', 'price' => 7999, 'room_count' => 350, 'status' => true]),
            ]);
        }

        // 2. Seed 5 Distributors
        $distributorData = [
            ['name' => 'Apex Media Hospitality', 'email' => 'apex.media@digihotel.com', 'phone' => '+91 9811122233'],
            ['name' => 'TechnoServe Fleet Solutions', 'email' => 'technoserve@digihotel.com', 'phone' => '+91 9822233344'],
            ['name' => 'Prime Screen Connect', 'email' => 'prime.connect@digihotel.com', 'phone' => '+91 9833344455'],
            ['name' => 'CloudGate Display Networks', 'email' => 'cloudgate@digihotel.com', 'phone' => '+91 9844455566'],
            ['name' => 'Horizon AV Media Hub', 'email' => 'horizon.av@digihotel.com', 'phone' => '+91 9855566677'],
        ];

        $distributors = [];
        foreach ($distributorData as $d) {
            $user = User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'phone' => $d['phone'],
                    'password' => bcrypt('password123'),
                    'status' => true,
                    'commission_percentage' => 15.00,
                ]
            );
            try {
                $user->assignRole('distributor');
            } catch (\Throwable $e) {}
            $distributors[] = $user;
        }

        // 3. Seed 10 Real Hotel Properties across historical months (spread May -> Oct 2026)
        $hotelShowcases = [
            [
                'hotel_name' => 'The Grand Horizon Luxury Suites',
                'owner_name' => 'Vikram Singhania',
                'email' => 'grand.horizon@hotels.com',
                'phone' => '+91 9911002233',
                'city' => 'New Delhi',
                'hotel_location' => 'Diplomatic Enclave, Chanakyapuri, New Delhi',
                'room_count' => 120,
                'months_ago' => 5,
                'distributor_idx' => 0,
                'plan_idx' => 2,
            ],
            [
                'hotel_name' => 'The Leela Executive Resort',
                'owner_name' => 'Ananya Oberoi',
                'email' => 'leela.resort@hotels.com',
                'phone' => '+91 9922113344',
                'city' => 'Mumbai',
                'hotel_location' => 'Bandra Kurla Complex, Bandra East, Mumbai',
                'room_count' => 160,
                'months_ago' => 4,
                'distributor_idx' => 1,
                'plan_idx' => 3,
            ],
            [
                'hotel_name' => 'Taj Vivanta Silicon Oasis',
                'owner_name' => 'Rohan Murthy',
                'email' => 'taj.vivanta@hotels.com',
                'phone' => '+91 9933224455',
                'city' => 'Bengaluru',
                'hotel_location' => 'Whitefield Tech Corridor, Bengaluru',
                'room_count' => 95,
                'months_ago' => 4,
                'distributor_idx' => 2,
                'plan_idx' => 1,
            ],
            [
                'hotel_name' => 'ITC Maratha Heritage Stay',
                'owner_name' => 'Sunil Chawla',
                'email' => 'itc.maratha@hotels.com',
                'phone' => '+91 9944335566',
                'city' => 'Mumbai',
                'hotel_location' => 'Sahar Airport Road, Andheri East, Mumbai',
                'room_count' => 140,
                'months_ago' => 3,
                'distributor_idx' => 0,
                'plan_idx' => 2,
            ],
            [
                'hotel_name' => 'Marriott Courtyard Cybercity',
                'owner_name' => 'Kavita Menon',
                'email' => 'marriott.courtyard@hotels.com',
                'phone' => '+91 9955446677',
                'city' => 'Gurugram',
                'hotel_location' => 'DLF Phase 2, Cyber Hub, Gurugram',
                'room_count' => 85,
                'months_ago' => 2,
                'distributor_idx' => 3,
                'plan_idx' => 1,
            ],
            [
                'hotel_name' => 'Novotel Marina Bay Suites',
                'owner_name' => 'Amitabh Sen',
                'email' => 'novotel.marina@hotels.com',
                'phone' => '+91 9966557788',
                'city' => 'Kolkata',
                'hotel_location' => 'Action Area 1, New Town, Rajarhat, Kolkata',
                'room_count' => 70,
                'months_ago' => 2,
                'distributor_idx' => 4,
                'plan_idx' => 0,
            ],
            [
                'hotel_name' => 'Radisson Blu Coastal Palms',
                'owner_name' => 'Pooja Fernandes',
                'email' => 'radisson.palms@hotels.com',
                'phone' => '+91 9977668899',
                'city' => 'Goa',
                'hotel_location' => 'Candolim Beach Road, North Goa',
                'room_count' => 110,
                'months_ago' => 1,
                'distributor_idx' => 1,
                'plan_idx' => 2,
            ],
            [
                'hotel_name' => 'Hyatt Regency Pearl Palace',
                'owner_name' => 'Tariq Al-Mansoor',
                'email' => 'hyatt.pearl@hotels.com',
                'phone' => '+91 9988779900',
                'city' => 'Hyderabad',
                'hotel_location' => 'HITEC City, Madhapur, Hyderabad',
                'room_count' => 135,
                'months_ago' => 1,
                'distributor_idx' => 2,
                'plan_idx' => 2,
            ],
            [
                'hotel_name' => 'Trident Nariman Point Premier',
                'owner_name' => 'Cyrus Mistry',
                'email' => 'trident.nariman@hotels.com',
                'phone' => '+91 9999880011',
                'city' => 'Mumbai',
                'hotel_location' => 'Marine Drive, Nariman Point, South Mumbai',
                'room_count' => 190,
                'months_ago' => 0,
                'distributor_idx' => 0,
                'plan_idx' => 3,
            ],
            [
                'hotel_name' => 'The Oberoi Grand View Heights',
                'owner_name' => 'Devendra Rathore',
                'email' => 'oberoi.heights@hotels.com',
                'phone' => '+91 9900112233',
                'city' => 'Jaipur',
                'hotel_location' => 'Tonk Road, Central Heritage Zone, Jaipur',
                'room_count' => 105,
                'months_ago' => 0,
                'distributor_idx' => 3,
                'plan_idx' => 1,
            ],
        ];

        $hotels = [];
        foreach ($hotelShowcases as $h) {
            $createdDate = Carbon::now()->startOfMonth()->subMonths($h['months_ago'])->addDays(rand(2, 20));
            $plan = $plans[$h['plan_idx']] ?? $plans->first();
            $distributor = $distributors[$h['distributor_idx']];

            $hotel = HotelAdmin::updateOrCreate(
                ['email' => $h['email']],
                [
                    'hotel_name' => $h['hotel_name'],
                    'owner_name' => $h['owner_name'],
                    'phone' => $h['phone'],
                    'city' => $h['city'],
                    'hotel_location' => $h['hotel_location'],
                    'room_count' => $h['room_count'],
                    'distributor_id' => $distributor->id,
                    'plan_id' => $plan->id,
                    'payment_status' => 'paid',
                    'approval_status' => 'approved',
                    'status' => true,
                    'password' => bcrypt('password123'),
                    'purchase_date' => $createdDate,
                    'expiry_date' => $createdDate->copy()->addYear(),
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]
            );
            $hotels[] = $hotel;

            // Log distributor sales transaction for this onboarding
            DistributorSale::updateOrCreate(
                ['hotel_id' => $hotel->id, 'distributor_id' => $distributor->id],
                [
                    'plan_id' => $plan->id,
                    'amount' => $plan->price,
                    'payment_status' => 'paid',
                    'payment_method' => 'bank_transfer',
                    'license_key_issued' => 'LIC-' . strtoupper(bin2hex(random_bytes(4))),
                    'notes' => 'Onboarding package licensed via ' . $distributor->name,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]
            );

            // Seed 6 to 14 Connected Smart TVs per hotel
            $numDevices = rand(6, 14);
            for ($dIdx = 1; $dIdx <= $numDevices; $dIdx++) {
                $roomNo = (string) (100 * rand(1, 8) + $dIdx);
                $deviceToken = 'DEV_' . $hotel->id . '_' . $roomNo . '_' . bin2hex(random_bytes(6));
                ConnectedDevice::updateOrCreate(
                    ['hotel_admin_id' => $hotel->id, 'room_no' => $roomNo],
                    [
                        'device_id' => 'TV_' . $hotel->id . '_' . $roomNo,
                        'api_token' => $deviceToken,
                        'mac_address' => sprintf('%02X:%02X:%02X:%02X:%02X:%02X', rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)),
                        'model' => ['SmartTV_4K_Pro', 'Bravia_XR_55', 'QLED_UHD_65', 'Mi_TV_Pro_50'][rand(0, 3)],
                        'brand' => ['Sony', 'Samsung', 'LG', 'Xiaomi'][rand(0, 3)],
                        'os_version' => 'Android TV 12',
                        'created_at' => $createdDate->copy()->addHours(rand(1, 48)),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
