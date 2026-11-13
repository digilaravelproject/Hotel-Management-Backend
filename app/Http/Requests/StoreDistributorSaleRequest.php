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
            'sale_type' => 'nullable|in:subscription,addon',
            'hotel_id' => 'required|exists:hotel_admins,id',
            'plan_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('plans', 'id')->where('status', true),
            ],
            'tv_count' => 'nullable|integer|min:1|max:10000',
            'additional_tvs' => 'nullable|integer|min:1|max:5000',
            'duration_months' => 'required_unless:sale_type,addon|nullable|integer|in:1,3,6,12,24,36',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:online_gateway',
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
            'tv_count' => 'Number of Connected TVs',
            'duration_months' => 'Plan Duration',
            'amount' => 'Package Amount',
            'payment_method' => 'Payment Method',
        ];
    }
}
