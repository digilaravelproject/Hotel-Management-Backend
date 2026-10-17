<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Display all gateway configurations with toggles.
     */
    public function index()
    {
        $gateways = PaymentGateway::all();
        return view('super_admin.settings.payment', compact('gateways'));
    }

    /**
     * Update credentials and toggle active gateway.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'gateways' => 'required|array',
            'gateways.*.name' => 'required|string|max:100',
            'gateways.*.is_enabled' => 'boolean',
            'gateways.*.api_key' => 'nullable|string',
            'gateways.*.api_secret' => 'nullable|string',
            'gateways.*.merchant_id' => 'nullable|string',
            'gateways.*.mode' => 'nullable|in:sandbox,live',
        ]);

        // If toggling, only one can be active
        $enabledCode = null;
        foreach ($validated['gateways'] as $code => $data) {
            if (!empty($data['is_enabled'])) {
                $enabledCode = $code;
                break;
            }
        }

        if ($enabledCode) {
            PaymentGateway::where('code', '!=', $enabledCode)->update(['is_enabled' => false]);
        }

        foreach ($validated['gateways'] as $code => $data) {
            $gateway = PaymentGateway::where('code', $code)->first();
            if ($gateway) {
                $gateway->update([
                    'name' => $data['name'],
                    'is_enabled' => $data['is_enabled'] ?? false,
                    'api_key' => !empty($data['api_key']) ? $data['api_key'] : $gateway->api_key,
                    'api_secret' => !empty($data['api_secret']) ? $data['api_secret'] : $gateway->api_secret,
                    'merchant_id' => $data['merchant_id'] ?? $gateway->merchant_id,
                    'mode' => $data['mode'] ?? $gateway->mode,
                ]);
            }
        }

        return redirect()->route('super-admin.payment-settings.index')
                         ->with('success', 'Payment gateway settings updated successfully. Active gateway will be used for all new payments.');
    }
}
