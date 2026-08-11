<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\FirebaseSetting;
use App\Services\FirebaseFcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseSettingsController extends Controller
{
    protected FirebaseFcmService $fcmService;

    public function __construct(FirebaseFcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Show Firebase FCM Settings Dashboard for Super Admin.
     */
    public function index()
    {
        $setting = FirebaseSetting::latest()->first();
        $parsedConfig = null;

        if ($setting && $setting->service_account_json) {
            $json = json_decode($setting->service_account_json, true);
            if (is_array($json)) {
                $parsedConfig = [
                    'project_id' => $json['project_id'] ?? 'N/A',
                    'client_email' => $json['client_email'] ?? 'N/A',
                    'type' => $json['type'] ?? 'N/A',
                    'has_private_key' => !empty($json['private_key']),
                ];
            }
        }

        return view('super_admin.firebase_settings', [
            'setting' => $setting,
            'parsedConfig' => $parsedConfig,
        ]);
    }

    /**
     * Store or update Firebase Service Account Credentials JSON File.
     */
    public function update(Request $request)
    {
        $request->validate([
            'credentials_file' => 'required_without:json_text|file|max:2048',
            'json_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $jsonContent = null;

        if ($request->hasFile('credentials_file')) {
            $file = $request->file('credentials_file');
            $jsonContent = file_get_contents($file->getRealPath());
        } elseif ($request->filled('json_text')) {
            $jsonContent = $request->input('json_text');
        }

        if (!$jsonContent) {
            return redirect()->back()->with('error', 'Please upload a valid service-account.json file or paste the JSON text.');
        }

        // Validate JSON Structure
        $parsed = json_decode($jsonContent, true);
        if (!is_array($parsed)) {
            return redirect()->back()->with('error', 'Invalid JSON file format. Could not parse JSON.');
        }

        if (!isset($parsed['project_id']) || !isset($parsed['private_key']) || !isset($parsed['client_email'])) {
            return redirect()->back()->with('error', 'Invalid Firebase credentials JSON! Missing required fields: project_id, private_key, or client_email.');
        }

        if (isset($parsed['type']) && $parsed['type'] !== 'service_account') {
            return redirect()->back()->with('error', 'Uploaded JSON must be a Firebase "service_account" key file.');
        }

        // Store or update in database (automatically encrypted by model)
        $setting = FirebaseSetting::firstOrNew();
        $setting->project_id = $parsed['project_id'];
        $setting->service_account_json = json_encode($parsed);
        $setting->is_active = $request->has('is_active') ? (bool) $request->input('is_active') : true;
        $setting->save();

        // Clear cached access tokens
        Cache::forget('firebase_fcm_access_token_' . md5($parsed['client_email']));

        return redirect()->route('super-admin.firebase-settings.index')
            ->with('success', 'Firebase Service Account credentials updated & encrypted successfully!');
    }

    /**
     * Send a test silent FCM data push notification.
     */
    public function testPush(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:topic,token',
            'target_value' => 'required|string',
        ]);

        $targetType = $request->input('target_type');
        $targetValue = trim($request->input('target_value'));

        $testData = [
            'scope' => 'TEST_NOTIFICATION',
            'message' => 'Super Admin Test FCM Sync',
            'timestamp' => (string) now()->timestamp,
        ];

        $success = false;
        if ($targetType === 'topic') {
            $success = $this->fcmService->sendToTopic($targetValue, $testData);
        } else {
            $success = $this->fcmService->sendToToken($targetValue, $testData);
        }

        if ($success) {
            return redirect()->back()->with('success', "Test FCM Data Push sent successfully to {$targetType}: {$targetValue}");
        }

        return redirect()->back()->with('error', "Failed to send Test FCM Data Push. Please check system logs and Firebase credentials.");
    }

    /**
     * Send a test sync document directly to Firebase Firestore Database.
     */
    public function testFirestore(Request $request)
    {
        $firestore = app(\App\Services\FirebaseFirestoreService::class);
        $collectionPath = 'hotel_1';
        $testRoomId = 'room_105';

        $testData = [
            'scope' => 'TEST_FIRESTORE_SUBCOLLECTION',
            'room_no' => '105',
            'device_id' => 'd6dd133551507f77',
            'updated_at' => now()->toIso8601String(),
            'data' => [
                'test_message' => 'Super Admin Firestore Sub-Collection Connection Success!',
                'timestamp' => now()->timestamp,
            ]
        ];

        $result = $firestore->syncDocument($collectionPath, $testRoomId, $testData);

        if (is_array($result) && ($result['success'] ?? false)) {
            return redirect()->back()->with('success', "Live Firestore Test Document successfully created! Check path: '{$collectionPath}/{$testRoomId}'.");
        }

        $errorDetail = is_array($result) ? ($result['message'] ?? 'Unknown Error') : 'Firestore connection failed.';

        return redirect()->back()->with('error', "Failed to sync to Firebase Firestore: {$errorDetail}");
    }
}
