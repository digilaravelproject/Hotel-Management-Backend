<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Facades\Storage;

class TvTemplateController extends Controller
{
    /**
     * Fetch the latest template version check info.
     */
    public function checkVersion(Request $request)
    {
        $latest = TvTemplate::query()
            ->where('is_active', '=', true)
            ->orderBy('id', 'desc')
            ->first();

        if (!$latest) {
            return response()->json([
                'status' => false,
                'message' => 'No active templates available at this moment.',
                'latest_version' => null,
                'old_version' => null,
                'download_url' => null,
                'uploaded_at' => null,
            ], 404);
        }

        // Get the previous version (if any)
        $previous = TvTemplate::query()
            ->where('id', '<', $latest->id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Template version details fetched successfully.',
            'latest_version' => $latest->version,
            'old_version' => $previous ? $previous->version : null,
            'download_url' => Storage::url($latest->file_path),
            'uploaded_at' => $latest->created_at->toIso8601String(),
        ], 200);
    }
}
