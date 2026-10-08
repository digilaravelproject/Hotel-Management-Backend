<?php

namespace App\Http\Requests;

class UpdateHotelRequest extends BaseHotelRequest
{
    /**
     * Update hotel validation rules merged with common rules.
     */
    public function rules(): array
    {
        $hotelId = $this->route('hotel') ?? $this->route('id');
        if (is_object($hotelId)) {
            $hotelId = $hotelId->id;
        }

        return array_merge($this->commonRules(), [
            'email' => 'required|email|max:255|unique:hotel_admins,email,' . $hotelId,
            'password' => 'nullable|string|min:6',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:purchase_date',
            'slider_images' => 'nullable|array|max:10',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);
    }
}
