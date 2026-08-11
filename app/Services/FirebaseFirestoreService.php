<?php

namespace App\Services;

use App\Models\FirebaseSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseFirestoreService
{
    protected FirebaseFcmService $fcmService;

    public function __construct(FirebaseFcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Get dynamic Firebase project ID.
     */
    protected function getProjectId(): ?string
    {
        $setting = FirebaseSetting::getActiveSetting();
        if ($setting && $setting->project_id) {
            return $setting->project_id;
        }

        $path = storage_path('app/firebase/service-account.json');
        if (file_exists($path)) {
            $json = json_decode(file_get_contents($path), true);
            return $json['project_id'] ?? null;
        }

        return null;
    }

    /**
     * Convert standard PHP associative array to Firestore REST API value format.
     */
    protected function formatFirestoreValue($value): array
    {
        if (is_null($value)) {
            return ['nullValue' => null];
        }
        if (is_bool($value)) {
            return ['booleanValue' => $value];
        }
        if (is_int($value)) {
            return ['integerValue' => (string) $value];
        }
        if (is_float($value)) {
            return ['doubleValue' => $value];
        }
        if (is_string($value)) {
            return ['stringValue' => $value];
        }
        if (is_array($value)) {
            // Check if associative array (map) or sequential array (list)
            if (array_keys($value) !== range(0, count($value) - 1)) {
                $fields = [];
                foreach ($value as $k => $v) {
                    $fields[(string) $k] = $this->formatFirestoreValue($v);
                }
                return ['mapValue' => ['fields' => $fields]];
            } else {
                $values = [];
                foreach ($value as $v) {
                    $values[] = $this->formatFirestoreValue($v);
                }
                return ['arrayValue' => ['values' => $values]];
            }
        }

        return ['stringValue' => (string) $value];
    }

    /**
     * Sync data object directly into Firestore document (e.g., collection "hotels", document "hotel_1").
     */
    public function syncDocument(string $collection, string $documentId, array $data): bool
    {
        $projectId = $this->getProjectId();
        if (!$projectId) {
            Log::info('Firebase project ID not available for Firestore sync.');
            return false;
        }

        $accessToken = $this->fcmService->getAccessToken();
        if (!$accessToken) {
            Log::warning('Firebase OAuth token unavailable for Firestore sync.');
            return false;
        }

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/{$collection}/{$documentId}";

        // Format fields for Firestore REST API
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[$key] = $this->formatFirestoreValue($val);
        }

        $body = [
            'fields' => $fields,
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->patch($url, $body);

        if ($response->successful()) {
            Log::info("Firestore document successfully synced: {$collection}/{$documentId}");
            return [
                'success' => true,
                'message' => "Document successfully synced to {$collection}/{$documentId}",
            ];
        }

        $errorBody = $response->json();
        $errorMsg = $errorBody['error']['message'] ?? $response->body();

        Log::error("Failed to sync Firestore document: {$collection}/{$documentId}", [
            'status' => $response->status(),
            'response' => $response->body(),
        ]);

        return [
            'success' => false,
            'message' => "Google API Error ({$response->status()}): {$errorMsg}",
        ];
    }
}
