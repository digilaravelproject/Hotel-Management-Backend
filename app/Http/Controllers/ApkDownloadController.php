<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApkDownloadController extends Controller
{
    /**
     * Download the PAX TV Hospitality APK using a secure signed URL.
     * Link expires in exactly 7 days and blocks if altered, expired, or unsigned.
     */
    public function download(Request $request)
    {
        // 1. Verify that the URL has an authentic, unexpired HMAC signature
        if (! $this->isValidApkSignature($request)) {
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

    /**
     * Validate the HMAC signature resiliently against HTTP/HTTPS scheme switches,
     * reverse proxy terminations, domain aliases, and email tracking query parameters.
     */
    protected function isValidApkSignature(Request $request): bool
    {
        // 1. Check expiration timestamp
        $expires = $request->query('expires');
        if (! $expires || ! is_numeric($expires)) {
            return false;
        }

        // Check if the 7-day period is actually in the past
        if (time() > (int) $expires) {
            return false;
        }

        // 2. Native Laravel checks (Absolute and Relative)
        if ($request->hasValidSignature()) {
            return true;
        }

        if ($request->hasValidSignature(false)) {
            return true;
        }

        // 3. Scheme, Host, and Query parameter normalization check
        $signature = $request->query('signature');
        if (! $signature || ! is_string($signature)) {
            return false;
        }

        $rawKeys = (array) config('app.key');
        if ($prev = config('app.previous_keys')) {
            $rawKeys = array_merge($rawKeys, (array) $prev);
        }

        $path = '/' . ltrim($request->path(), '/');

        // Extract raw query string without signature
        $rawQuery = (string) ($request->server->get('VAPOR_RAW_QUERY_STRING') ?? $request->server->get('QUERY_STRING') ?? '');
        $rawClean = preg_replace('/(&|\?)?signature=[^&]*/', '', $rawQuery);
        $rawClean = ltrim($rawClean, '&?');

        // Extract parsed parameters without signature
        $params = $request->query();
        unset($params['signature']);
        $builtQuery = http_build_query($params);

        // Also query stripped of external marketing/email tracking parameters
        $cleanParams = $params;
        $trackingKeys = [
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
            'gclid', 'fbclid', 'msclkid', 'mc_cid', 'mc_eid', '_ga', 'click_id', 'ref_src'
        ];
        foreach ($trackingKeys as $tk) {
            unset($cleanParams[$tk]);
        }
        $strippedTrackingQuery = http_build_query($cleanParams);

        $queriesToTest = array_unique(array_filter([$rawClean, $builtQuery, $strippedTrackingQuery]));
        $hostsToTest = array_unique([$request->getHost(), 'paxtvlauncher.com', 'app.paxtvlauncher.com', 'localhost']);
        $schemesToTest = ['http', 'https'];

        foreach ($rawKeys as $key) {
            if (empty($key)) continue;

            $keysToTest = [$key];
            if (str_starts_with($key, 'base64:')) {
                $keysToTest[] = base64_decode(substr($key, 7));
            }

            foreach ($keysToTest as $k) {
                foreach ($queriesToTest as $q) {
                    // Test relative URL format
                    $targetRel = $path . '?' . $q;
                    if (hash_equals(hash_hmac('sha256', $targetRel, $k), $signature)) {
                        return true;
                    }

                    // Test absolute URL across scheme/host combinations
                    foreach ($hostsToTest as $h) {
                        foreach ($schemesToTest as $s) {
                            $targetAbs = $s . '://' . $h . $path . '?' . $q;
                            if (hash_equals(hash_hmac('sha256', $targetAbs, $k), $signature)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }
}
