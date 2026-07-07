<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\TvLoginResource;

class TvTemplateController extends Controller
{
    /**
     * Fetch the latest template version check info.
     * Securely verifies user via Auth Token and returns the synchronized payload.
     */
    public function checkVersion(Request $request)
    {
        $device = $request->input('current_device');
        $hotel = $request->input('current_hotel');

        if (!$device || !$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Invalid token or associated hotel admin not found.'
            ], 401);
        }

        // Fetch latest active template version
        $latest = \App\Models\TvTemplate::query()
            ->where('is_active', '=', true)
            ->orderBy('id', 'desc')
            ->first();

        $clientVersion = $request->query('version');
        $isUpdateAvailable = false;

        if ($latest && $clientVersion !== null) {
            // Check if server version is greater than client's version
            $isUpdateAvailable = version_compare($latest->version, $clientVersion, '>');
        }

        // Eager load the plan relation to avoid N+1 query issues
        $hotel->loadMissing('plan');

        // Merge version comparison result into request for the resource to read
        $request->merge([
            'is_update_available' => $isUpdateAvailable,
        ]);

        return new TvLoginResource([
            'device' => $device,
            'hotel' => $hotel,
            'message' => 'Template version details fetched successfully.',
        ]);
    }
}
