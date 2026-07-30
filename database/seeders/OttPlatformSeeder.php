<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OttPlatform;

class OttPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'Google Play Store', 'package_name' => 'com.android.vending'],
            ['name' => 'Netflix', 'package_name' => 'com.netflix.mediaclient'],
            ['name' => 'Disney+ Hotstar', 'package_name' => 'in.startv.hotstar'],
            ['name' => 'Amazon Prime Video', 'package_name' => 'com.amazon.avod.thirdpartyclient'],
            ['name' => 'Zee5', 'package_name' => 'com.graymatrix.did'],
            ['name' => 'Sony LIV', 'package_name' => 'com.sony.liv'],
            ['name' => 'JioCinema', 'package_name' => 'com.jio.media.ondemand'],
            ['name' => 'Aha', 'package_name' => 'ka.alua.aha'],
            ['name' => 'Sun NXT', 'package_name' => 'com.suntv.sunnxt'],
            ['name' => 'MX Player', 'package_name' => 'com.mxtech.videoplayer.ad'],
            ['name' => 'Discovery+', 'package_name' => 'com.discovery.discoveryplus.mobile'],
            ['name' => 'ALTBalaji', 'package_name' => 'com.balaji.alt'],
            ['name' => 'Eros Now', 'package_name' => 'com.erosnow'],
            ['name' => 'Hungama Play', 'package_name' => 'com.hungama.myplay.activity'],
            ['name' => 'Hoichoi', 'package_name' => 'com.viewlift.hoichoi'],
            ['name' => 'Planet Marathi', 'package_name' => 'com.planetmarathi.ott'],
            ['name' => 'Chaupal', 'package_name' => 'com.chaupal.app'],
            ['name' => 'ManoramaMAX', 'package_name' => 'com.manoramamax.app'],
            ['name' => 'Voot', 'package_name' => 'com.tv.v18.viola'],
        ];

        foreach ($platforms as $platform) {
            OttPlatform::updateOrCreate(
                ['package_name' => $platform['package_name']],
                ['name' => $platform['name'], 'status' => true]
            );
        }
    }
}
