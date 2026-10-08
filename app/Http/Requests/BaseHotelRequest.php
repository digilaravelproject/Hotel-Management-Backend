<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Common hotel validation rules shared across create and update flows.
     */
    protected function commonRules(): array
    {
        return [
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'room_count' => 'nullable|integer|min:1|max:5000',
            'plan_id' => 'nullable|exists:plans,id',
            'distributor_id' => 'nullable|exists:users,id',
            'payment_status' => 'nullable|in:pending,paid',
            'approval_status' => 'nullable|in:pending,approved,disapproved',
            'description' => 'nullable|string|max:1000',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ];
    }

    /**
     * Common friendly attribute names for validation messages.
     */
    public function attributes(): array
    {
        return [
            'owner_name' => 'Owner Name',
            'email' => 'Email Address',
            'password' => 'Account Password',
            'phone' => 'Phone Number',
            'hotel_name' => 'Hotel Client Name',
            'hotel_location' => 'Hotel Address / Location',
            'city' => 'City',
            'room_count' => 'Room / TV Limit',
            'plan_id' => 'Subscription Plan',
            'distributor_id' => 'Channel Partner / Distributor',
            'payment_status' => 'Payment Status',
            'approval_status' => 'Approval Status',
            'hotel_logo' => 'Hotel Brand Logo',
            'hotel_image' => 'Hotel Cover Image',
            'slider_images' => 'Slider Promotional Images',
            'duration_months' => 'Plan Duration',
            'payment_method' => 'Payment Method',
        ];
    }
}
