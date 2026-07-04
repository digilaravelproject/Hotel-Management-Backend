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

        // Format slider images asset URLs
        $sliders = [];
        if ($hotel->slider_images && is_array($hotel->slider_images)) {
            foreach ($hotel->slider_images as $path) {
                $sliders[] = asset($path);
            }
        }

        return [
            'status' => true,
            'message' => 'TV logged in successfully.',
            'auth' => [
                'token' => $device->api_token,
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
                    'logo_image' => $hotel->hotel_logo ? asset($hotel->hotel_logo) : null,
                    'cover_image' => $hotel->hotel_image ? asset($hotel->hotel_image) : null,
                    'slider_images' => $sliders,
                ],
            ]
        ];
    }
}
