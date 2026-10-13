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
            'license_key' => 'required_without:demo_login|string|nullable',
            'room_no' => 'required_without:demo_login|string|nullable|max:50',
            'deviceId' => 'required|string|max:100',
            'macAddress' => 'required|string|max:100',
            'ipAddress' => 'nullable|string|max:45',
            'model' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'osVersion' => 'nullable|string|max:50',
            'demo_login' => 'nullable|boolean',
            'is_demo' => 'nullable|boolean',
            'distributor_code' => 'nullable|string|max:50',
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
