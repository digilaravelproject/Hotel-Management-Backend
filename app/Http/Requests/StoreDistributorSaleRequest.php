<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistributorSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for distributor package sale.
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotel_admins,id',
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Friendly attribute names.
     */
    public function attributes(): array
    {
        return [
            'hotel_id' => 'Target Hotel',
            'plan_id' => 'Subscription Plan',
            'duration_months' => 'Plan Duration',
            'amount' => 'Package Amount',
            'payment_method' => 'Payment Method',
        ];
    }
}
