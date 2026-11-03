<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ott_platforms')) {
            return;
        }

        $packageMap = [
            'com.android.vending' => 'images/ott/google-play.svg',
            'com.netflix.mediaclient' => 'images/ott/netflix.svg',
            'in.startv.hotstar' => 'images/ott/hotstar.svg',
            'com.amazon.avod.thirdpartyclient' => 'images/ott/prime-video.svg',
            'com.graymatrix.did' => 'images/ott/zee5.svg',
            'com.sony.liv' => 'images/ott/sonyliv.svg',
            'com.jio.media.ondemand' => 'images/ott/jiocinema.svg',
            'ka.alua.aha' => 'images/ott/aha.svg',
            'com.suntv.sunnxt' => 'images/ott/sun-nxt.svg',
            'com.mxtech.videoplayer.ad' => 'images/ott/mx-player.svg',
            'com.discovery.discoveryplus.mobile' => 'images/ott/discovery-plus.svg',
            'com.balaji.alt' => 'images/ott/altbalaji.svg',
            'com.erosnow' => 'images/ott/eros-now.svg',
            'com.hungama.myplay.activity' => 'images/ott/hungama.svg',
            'com.viewlift.hoichoi' => 'images/ott/hoichoi.svg',
            'com.planetmarathi.ott' => 'images/ott/planet-marathi.svg',
            'com.chaupal.app' => 'images/ott/chaupal.svg',
            'com.manoramamax.app' => 'images/ott/manoramamax.svg',
            'com.tv.v18.viola' => 'images/ott/voot.svg',
        ];

        foreach ($packageMap as $pkg => $icon) {
            DB::table('ott_platforms')
                ->where('package_name', $pkg)
                ->whereNull('icon')
                ->update(['icon' => $icon]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to leave populated
    }
};

