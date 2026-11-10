<?php

namespace App\Http\Requests\HotelAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompleteHotelRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'desk_phone' => $this->desk_phone ? trim($this->desk_phone) : null,
            'owner_name' => $this->owner_name ? trim($this->owner_name) : null,
            'hotel_name' => $this->hotel_name ? trim($this->hotel_name) : null,
            'hotel_location' => $this->hotel_location ? trim($this->hotel_location) : null,
            'city' => $this->city ? trim($this->city) : null,
            'postal_code' => $this->postal_code ? trim($this->postal_code) : null,
            'room_count' => $this->room_count ? (int) $this->room_count : 1,
            'duration_months' => $this->duration_months ? (int) $this->duration_months : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'owner_name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:hotel_admins,email',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-()]+$/'],
            'password' => 'required|string|min:6',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'desk_phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s\-()]+$/'],
            'room_count' => 'required|integer|min:1|max:5000',
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'nullable|integer|min:1|max:60',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'order_id' => 'nullable|string|max:100',
            'razorpay_order_id' => 'nullable|string|max:100',
            'razorpay_payment_id' => 'nullable|string|max:100',
            'razorpay_signature' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom friendly attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'owner_name' => 'Owner Name',
            'email' => 'Email Address',
            'phone' => 'Mobile Phone Number',
            'password' => 'Account Password',
            'hotel_name' => 'Hotel Property Name',
            'hotel_location' => 'Full Hotel Address',
            'city' => 'City',
            'postal_code' => 'PIN / Postal Code',
            'desk_phone' => 'Front Desk Phone',
            'room_count' => 'Number of Connected Smart TVs',
            'plan_id' => 'Subscription Plan',
            'hotel_logo' => 'Hotel Brand Logo',
            'hotel_image' => 'Hotel Photo / Cover',
        ];
    }

    /**
     * Handle a failed validation attempt with JSON response.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422));
    }
}
