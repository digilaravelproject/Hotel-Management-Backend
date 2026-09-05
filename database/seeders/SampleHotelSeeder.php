<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;
use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;

class SampleHotelSeeder extends Seeder
{
    public function run(): void
    {
        $bom = Airport::where('iata_code', 'BOM')->first();
        $nmi = Airport::where('iata_code', 'NMI')->first();

        $hotel = HotelAdmin::updateOrCreate(
            ['email' => 'admin@luxuryhotel.com'],
            [
                'owner_name' => 'Rajesh Sharma',
                'hotel_name' => 'The Grand Luxury Hotel & Suites',
                'hotel_location' => 'Bandra Kurla Complex, Mumbai, Maharashtra 400051',
                'city' => 'Mumbai',
                'phone' => '+91 98765 43210',
                'password' => bcrypt('password123'),
                'primary_airport_id' => $bom ? $bom->id : null,
                'secondary_airport_id' => $nmi ? $nmi->id : null,
                'room_count' => 100,
                'approval_status' => 'approved',
                'status' => true,
            ]
        );

        $device = ConnectedDevice::updateOrCreate(
            ['api_token' => 'lyEbRgvofHcxzjSeJM9B4cCuSOAXkA5oq3EQOfb5027rDFfp6BPVaEa3ZNMgYExCfF9O82JuvlocqsMz'],
            [
                'hotel_admin_id' => $hotel->id,
                'room_no' => '502',
                'device_id' => 'TV_ROOM_502',
                'mac_address' => 'AA:BB:CC:DD:EE:01',
                'model' => 'SmartTV_4K',
                'brand' => 'Sony Bravia',
                'os_version' => 'Android 12 TV',
            ]
        );

        echo "Seeded Hotel ID: " . $hotel->id . ", Device ID: " . $device->id . "\n";
    }
}
