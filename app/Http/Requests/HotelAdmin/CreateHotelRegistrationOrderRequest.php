<?php

namespace App\Http\Requests\HotelAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateHotelRegistrationOrderRequest extends FormRequest
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
            'owner_name' => $this->owner_name ? trim($this->owner_name) : null,
            'hotel_name' => $this->hotel_name ? trim($this->hotel_name) : null,
            'city' => $this->city ? trim($this->city) : null,
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
            'plan_id' => 'required|exists:plans,id',
            'room_count' => 'required|integer|min:1|max:5000',
            'duration_months' => 'nullable|integer|min:1|max:60',
            'owner_name' => 'nullable|string|max:255',
            'hotel_name' => 'nullable|string|max:255',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s\-()]+$/'],
            'city' => 'nullable|string|max:100',
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
            'plan_id' => 'Subscription Plan',
            'room_count' => 'Number of Connected Smart TVs',
            'duration_months' => 'Plan Duration (Months)',
            'owner_name' => 'Owner Name',
            'hotel_name' => 'Hotel Property Name',
            'email' => 'Email Address',
            'phone' => 'Contact Number',
            'city' => 'City',
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
