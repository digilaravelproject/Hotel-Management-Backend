<?php

namespace App\Http\Requests;

class StoreHotelRequest extends BaseHotelRequest
{
    /**
     * Store hotel validation rules merged with common rules.
     */
    public function rules(): array
    {
        return array_merge($this->commonRules(), [
            'email' => 'required|email|max:255|unique:hotel_admins,email',
            'password' => 'required|string|min:6',
        ]);
    }
}
