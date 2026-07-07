<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvLoginResource extends JsonResource
{
    /**
     * Disable JSON wrapping.
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hotel = $this['hotel'];
        $device = $this['device'];

        // Convert slider images to Base64 strings
        $sliders = [];
        if ($hotel->slider_images && is_array($hotel->slider_images)) {
            foreach ($hotel->slider_images as $path) {
                $base64 = $this->convertToBase64($path);
                if ($base64) {
                    $sliders[] = $base64;
                }
            }
        }

        $plan = $hotel->plan;

        // Fetch template details dynamically
        $latest = \App\Models\TvTemplate::query()
            ->where('is_active', '=', true)
            ->orderBy('id', 'desc')
            ->first();

        $previous = $latest ? \App\Models\TvTemplate::query()
            ->where('id', '<', $latest->id)
            ->orderBy('id', 'desc')
            ->first() : null;

        return [
            'status' => true,
            'message' => 'TV logged in successfully.',
            'auth' => [
                'token' => $device->api_token,
            ],
            'template' => [
                'latest_version' => $latest ? $latest->version : null,
                'old_version' => $previous ? $previous->version : null,
                'download_url' => $latest ? url(\Illuminate\Support\Facades\Storage::url($latest->file_path)) : null,
                'uploaded_at' => $latest ? $latest->created_at->toIso8601String() : null,
                'is_update_available' => (bool) $request->input('is_update_available', false),
            ],
            'device' => [
                'room_no' => $device->room_no,
                'device_id' => $device->device_id,
                'mac_address' => $device->mac_address,
                'ip_address' => $device->ip_address,
                'model' => $device->model,
                'brand' => $device->brand,
                'os_version' => $device->os_version,
            ],
            'hotel' => [
                'hotel_name' => $hotel->hotel_name,
                'hotel_location' => $hotel->hotel_location,
                'description' => $hotel->description,
                'owner_name' => $hotel->owner_name,
                'email' => $hotel->email,
                'phone' => $hotel->phone,
                'media' => [
                    'logo_image' => $this->convertToBase64($hotel->hotel_logo),
                    'cover_image' => $this->convertToBase64($hotel->hotel_image),
                    'slider_images' => $sliders,
                ],
                'active_plan' => [
                    'plan_name' => $plan ? $plan->name : 'N/A',
                    'plan_price' => $plan ? $plan->price : '0.00',
                    'purchase_date' => $hotel->created_at ? $hotel->created_at->toIso8601String() : null,
                    'expiry_date' => $hotel->created_at ? $hotel->created_at->copy()->addDays(30)->toIso8601String() : null,
                ]
            ]
        ];
    }

    /**
     * Helper method to convert local image path to Base64 data URL.
     */
    private function convertToBase64(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $fullPath = public_path($path);

        if (file_exists($fullPath) && is_file($fullPath)) {
            try {
                $mimeType = mime_content_type($fullPath) ?: 'image/png';
                $data = base64_encode(file_get_contents($fullPath));
                return "data:{$mimeType};base64,{$data}";
            } catch (\Exception $e) {
                logger()->error("Base64 conversion failed for {$path}: " . $e->getMessage());
            }
        }

        return null;
    }
}
