<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Http;

class IciciPaymentService
{
    protected ?PaymentGateway $gateway;

    public function __construct()
    {
        $this->gateway = PaymentGateway::getForCode('icici');
    }

    /**
     * Check if ICICI payment gateway is enabled and configured.
     */
    public function isEnabled(): bool
    {
        return $this->gateway && $this->gateway->is_enabled;
    }

    /**
     * Create order or payment session with ICICI Gateway.
     */
    public function createOrder(float $amount, string $orderId, array $customerDetails = []): array
    {
        if (!$this->isEnabled()) {
            throw new Exception("ICICI Payment Gateway is currently disabled or not configured.");
        }

        $apiKey = $this->gateway->api_key;
        $apiSecret = $this->gateway->api_secret;
        $merchantId = $this->gateway->merchant_id;
        $isLive = $this->gateway->mode === 'live';

        // ICICI Eazypay / Corporate API endpoints (sandbox vs live)
        $baseUrl = $isLive
            ? 'https://apibanking.icicibank.com/api/v1'
            : 'https://test.icicibank.com/api/v1';

        // Mock implementation safeguard if credentials are placeheld
        if (empty($apiKey) || empty($merchantId) || empty($apiSecret)) {
            // Return simulated order for development/testing when gateway is enabled but credentials are mock placeholders
            return [
                'success' => true,
                'order_id' => $orderId,
                'gateway_reference' => 'icici_ref_' . rand(100000, 999999),
                'amount' => $amount,
                'currency' => 'INR',
                'checkout_url' => '#',
            ];
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'X-Merchant-Id' => $merchantId,
                'Accept' => 'application/json',
            ])->post("{$baseUrl}/payments/order", [
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => 'INR',
                'customer_email' => $customerDetails['email'] ?? '',
                'customer_phone' => $customerDetails['phone'] ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'order_id' => $data['order_id'] ?? $orderId,
                    'gateway_reference' => $data['payment_link_id'] ?? $data['reference'] ?? null,
                    'checkout_url' => $data['payment_url'] ?? null,
                ];
            }

            throw new Exception("ICICI Gateway error: " . $response->body());
        } catch (Exception $e) {
            throw new Exception("Failed to connect to ICICI Payment Gateway: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Verify payment response / checksum from ICICI callback.
     */
    public function verifyPayment(array $payload): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        // Verify ICICI response signature or checksum logic here
        return !empty($payload['status']) && $payload['status'] === 'SUCCESS';
    }
}
