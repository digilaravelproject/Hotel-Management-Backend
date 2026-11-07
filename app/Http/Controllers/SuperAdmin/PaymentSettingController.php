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
        $activeGateway = $gateways->firstWhere('is_enabled', true) ?? $gateways->first();
        return view('super_admin.settings.payment', compact('gateways', 'activeGateway'));
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
            'gateways.*.mode' => 'nullable|in:sandbox,live',
            'gateways.*.api_key' => 'nullable|string',
            'gateways.*.api_secret' => 'nullable|string',
            'gateways.*.merchant_id' => 'nullable|string',
            'gateways.*.sandbox_key' => 'nullable|string',
            'gateways.*.sandbox_secret' => 'nullable|string',
            'gateways.*.sandbox_merchant_id' => 'nullable|string',
            'gateways.*.live_key' => 'nullable|string',
            'gateways.*.live_secret' => 'nullable|string',
            'gateways.*.live_merchant_id' => 'nullable|string',
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
                $mode = $data['mode'] ?? $gateway->mode ?? 'sandbox';

                $extra = $gateway->extra_config ?? [];
                if (!is_array($extra)) {
                    $extra = [];
                }

                // Existing credentials
                $oldSandboxKey = $gateway->getSandboxKey();
                $oldSandboxSecret = $gateway->getSandboxSecret();
                $oldSandboxMerchantId = $gateway->getSandboxMerchantId();

                $oldLiveKey = $gateway->getLiveKey();
                $oldLiveSecret = $gateway->getLiveSecret();
                $oldLiveMerchantId = $gateway->getLiveMerchantId();

                // Resolve new Sandbox credentials (preserve previous if submitted blank or bullet placeholder)
                $newSandboxKey = isset($data['sandbox_key']) && trim($data['sandbox_key']) !== '' ? trim($data['sandbox_key']) : ($oldSandboxKey ?: ($mode === 'sandbox' ? $gateway->api_key : null));
                $newSandboxSecret = isset($data['sandbox_secret']) && trim($data['sandbox_secret']) !== '' ? trim($data['sandbox_secret']) : ($oldSandboxSecret ?: ($mode === 'sandbox' ? $gateway->api_secret : null));
                $newSandboxMerchantId = isset($data['sandbox_merchant_id']) && trim($data['sandbox_merchant_id']) !== '' ? trim($data['sandbox_merchant_id']) : $oldSandboxMerchantId;

                // Resolve new Live credentials (preserve previous if submitted blank or bullet placeholder)
                $newLiveKey = isset($data['live_key']) && trim($data['live_key']) !== '' ? trim($data['live_key']) : ($oldLiveKey ?: ($mode === 'live' ? $gateway->api_key : null));
                $newLiveSecret = isset($data['live_secret']) && trim($data['live_secret']) !== '' ? trim($data['live_secret']) : ($oldLiveSecret ?: ($mode === 'live' ? $gateway->api_secret : null));
                $newLiveMerchantId = isset($data['live_merchant_id']) && trim($data['live_merchant_id']) !== '' ? trim($data['live_merchant_id']) : $oldLiveMerchantId;

                // Legacy fallback if single fields were passed
                if (!empty($data['api_key'])) {
                    if ($mode === 'live') {
                        $newLiveKey = trim($data['api_key']);
                    } else {
                        $newSandboxKey = trim($data['api_key']);
                    }
                }
                if (!empty($data['api_secret'])) {
                    if ($mode === 'live') {
                        $newLiveSecret = trim($data['api_secret']);
                    } else {
                        $newSandboxSecret = trim($data['api_secret']);
                    }
                }
                if (isset($data['merchant_id']) && trim($data['merchant_id']) !== '') {
                    if ($mode === 'live') {
                        $newLiveMerchantId = trim($data['merchant_id']);
                    } else {
                        $newSandboxMerchantId = trim($data['merchant_id']);
                    }
                }

                // Update extra_config
                $extra['sandbox'] = [
                    'api_key' => $newSandboxKey,
                    'api_secret' => $newSandboxSecret,
                    'merchant_id' => $newSandboxMerchantId,
                ];
                $extra['live'] = [
                    'api_key' => $newLiveKey,
                    'api_secret' => $newLiveSecret,
                    'merchant_id' => $newLiveMerchantId,
                ];

                // Active credentials to store in primary columns for full backward compatibility
                $activeKey = $mode === 'live' ? $newLiveKey : $newSandboxKey;
                $activeSecret = $mode === 'live' ? $newLiveSecret : $newSandboxSecret;
                $activeMerchantId = $mode === 'live' ? $newLiveMerchantId : $newSandboxMerchantId;

                $gateway->update([
                    'name' => $data['name'],
                    'is_enabled' => $data['is_enabled'] ?? false,
                    'mode' => $mode,
                    'extra_config' => $extra,
                    'api_key' => $activeKey ?: $gateway->api_key,
                    'api_secret' => $activeSecret ?: $gateway->api_secret,
                    'merchant_id' => $activeMerchantId ?: $gateway->merchant_id,
                ]);
            }
        }

        return redirect()->route('super-admin.payment-settings.index')
                         ->with('success', 'Payment gateway settings updated successfully. Sandbox and Live credentials saved.');
    }
}
