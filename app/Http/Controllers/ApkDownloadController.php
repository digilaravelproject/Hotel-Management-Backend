<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApkDownloadController extends Controller
{
    /**
     * Download the PAX TV Hospitality APK using a secure signed URL.
     * Link expires in exactly 7 days and blocks if altered or unsigned.
     */
    public function download(Request $request)
    {
        // 1. Verify that the URL has an authentic, unexpired HMAC SHA-256 signature
        if (! $request->hasValidSignature()) {
            return response()->view('web.apk-expired', [
                'supportEmail' => 'support@paxtvlauncher.com',
            ], 403);
        }

        // 2. Locate APK file in the private storage vault
        $relativePath = 'private/apk/PAXTVHospitality.apk';
        $fullPath = storage_path('app/' . $relativePath);

        if (! file_exists($fullPath)) {
            abort(404, 'PAX TV Hospitality application package is temporarily unavailable. Please contact support.');
        }

        $fileSize = filesize($fullPath);

        // 3. Stream file safely as a binary Android APK download with exact progress indicators
        return response()->streamDownload(function () use ($fullPath) {
            $stream = fopen($fullPath, 'rb');
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 'PAXTVHospitality.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Length' => (string) $fileSize,
            'Content-Disposition' => 'attachment; filename="PAXTVHospitality.apk"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
