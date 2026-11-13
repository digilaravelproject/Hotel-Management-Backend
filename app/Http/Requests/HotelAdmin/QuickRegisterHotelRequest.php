<?php

namespace App\Http\Requests\HotelAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuickRegisterHotelRequest extends FormRequest
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
            'hotel_location' => $this->hotel_location ? trim($this->hotel_location) : null,
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
            'hotel_name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:hotel_admins,email',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-()]+$/'],
            'password' => 'required|string|min:6|confirmed',
            'city' => 'nullable|string|max:100',
            'hotel_location' => 'nullable|string|max:255',
            'room_count' => 'nullable|integer|min:1|max:5000',
            'plan_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('plans', 'id')->where(function ($q) {
                    $q->where('status', 1)->where('is_hidden', false);
                }),
            ],
            'terms' => 'nullable|accepted',
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
            'hotel_name' => 'Hotel Name',
            'email' => 'Email Address',
            'phone' => 'Mobile Phone Number',
            'password' => 'Account Password',
            'city' => 'City',
            'hotel_location' => 'Hotel Address',
            'room_count' => 'Number of Connected Smart TVs',
            'plan_id' => 'Subscription Plan',
            'terms' => 'Terms of Service Consent',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}

