<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TvLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'license_key' => 'required|string',
            'room_no' => 'required|string|max:50',
            'deviceId' => 'required|string|max:100',
            'macAddress' => 'required|string|max:100',
            'ipAddress' => 'nullable|string|max:45',
            'model' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'osVersion' => 'nullable|string|max:50',
            'fcmToken' => 'nullable|string',
            'fcm_token' => 'nullable|string',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}
