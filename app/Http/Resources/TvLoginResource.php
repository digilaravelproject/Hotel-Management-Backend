<?php

namespace App\Http\Resources;

use App\Models\Guest;
use App\Models\TvTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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

        $plan = $hotel->plan;
        $message = $this['message'] ?? 'TV logged in successfully.';

        // Look up active guest for the room and hotel
        $now = now();
        $activeGuest = Guest::query()->where('hotel_id', $hotel->id)
            ->where('room_number', $device->room_no)
            ->where('check_in_datetime', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('check_out_datetime')
                    ->orWhere('check_out_datetime', '>=', $now);
            })
            ->first();

        $guestInfo = $activeGuest ? [
            'name' => $activeGuest->name,
            'mobile_number' => $activeGuest->mobile_number,
            'check_in_datetime' => $activeGuest->check_in_datetime->toIso8601String(),
            'check_out_datetime' => $activeGuest->check_out_datetime ? $activeGuest->check_out_datetime->toIso8601String() : null,
        ] : null;

        // Fetch template details dynamically
        $latest = TvTemplate::query()
            ->where('is_active', '=', true)
            ->orderBy('id', 'desc')
            ->first();

        $previous = $latest ? TvTemplate::query()
            ->where('id', '<', $latest->id)
            ->orderBy('id', 'desc')
            ->first() : null;

        return [
            'status' => true,
            'message' => $message,
            'data' => [
                'auth' => [
                    'token' => $device->api_token,
                ],
                'template' => [
                    'latest_version' => $latest ? $latest->version : null,
                    'old_version' => $previous ? $previous->version : null,
                    'download_url' => $latest ? url(Storage::url($latest->file_path)) : null,
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
                        'logo_image' => $hotel->hotel_logo ? asset($hotel->hotel_logo) : null,
                        'cover_image' => $hotel->hotel_image ? asset($hotel->hotel_image) : null,
                        'slider_images' => $sliders,
                    ],
                    'active_plan' => [
                        'plan_name' => $plan ? $plan->name : 'N/A',
                        'plan_price' => $plan ? $plan->price : '0.00',
                        'purchase_date' => $hotel->purchase_date ? $hotel->purchase_date->toIso8601String() : ($hotel->created_at ? $hotel->created_at->toIso8601String() : null),
                        'expiry_date' => $hotel->expiry_date ? $hotel->expiry_date->toIso8601String() : ($hotel->created_at ? $hotel->created_at->copy()->addDays(30)->toIso8601String() : null),
                    ],
                ],
                'guest_info' => $guestInfo,
            ],
        ];
    }
}
