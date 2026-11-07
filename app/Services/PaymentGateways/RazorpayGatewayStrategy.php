<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Http;

class RazorpayGatewayStrategy implements PaymentGatewayInterface
{
    protected ?PaymentGateway $gatewayModel;

    public function __construct()
    {
        $this->gatewayModel = PaymentGateway::getForCode('razorpay');
    }

    public function getName(): string
    {
        return 'Razorpay';
    }

    public function getCode(): string
    {
        return 'razorpay';
    }

    public function isEnabled(): bool
    {
        return $this->gatewayModel && $this->gatewayModel->is_enabled;
    }

    public function createOrder(float $amount, string $orderId, array $customerDetails = []): array
    {
        if (!$this->isEnabled()) {
            throw new Exception("Razorpay gateway is disabled.");
        }

        $keyId = trim((string) ($this->gatewayModel->effective_api_key ?? ''));
        $keySecret = trim((string) ($this->gatewayModel->effective_api_secret ?? ''));

        if (empty($keyId)) {
            $keyId = trim((string) env('RAZORPAY_KEY_ID', env('RAZORPAY_KEY', '')));
        }
        if (empty($keySecret)) {
            $keySecret = trim((string) env('RAZORPAY_KEY_SECRET', env('RAZORPAY_SECRET', '')));
        }

        // Convert amount to paise for Razorpay
        $amountInPaise = (int) round($amount * 100);

        if (empty($keyId) && empty($keySecret)) {
            // Mock simulation for development
            return [
                'success' => true,
                'order_id' => 'order_' . rand(100000, 999999),
                'gateway_reference' => 'rzp_ref_' . rand(100000, 999999),
                'amount' => $amount,
                'currency' => 'INR',
                'checkout_url' => '#',
                'key_id' => 'rzp_test_mock',
            ];
        }

        if (empty($keyId)) {
            throw new Exception("Razorpay Key ID is missing. Please configure it in Super Admin Payment Settings.");
        }

        if (empty($keySecret)) {
            $mode = str_starts_with($keyId, 'rzp_test_') ? 'Sandbox' : 'Live';
            throw new Exception("Razorpay {$mode} Secret Key is missing. Please enter the Secret Key corresponding to Key ID (" . substr($keyId, 0, 12) . "...) in Super Admin Payment Settings.");
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => $orderId,
                    'notes' => [
                        'customer_email' => $customerDetails['email'] ?? '',
                        'customer_phone' => $customerDetails['phone'] ?? '',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'order_id' => $data['id'] ?? $orderId,
                    'gateway_reference' => $data['id'] ?? null,
                    'amount' => $amount,
                    'currency' => 'INR',
                    'key_id' => $keyId,
                ];
            }

            $resJson = $response->json();
            $errorDesc = $resJson['error']['description'] ?? $response->body();
            if (stripos($errorDesc, 'authentication failed') !== false) {
                $modeLabel = str_starts_with($keyId, 'rzp_test_') ? 'Test Mode' : 'Live Mode';
                throw new Exception("Razorpay Authentication Failed: The Key ID and Secret Key do not match. Please verify in your Razorpay Dashboard that this Secret belongs to your {$modeLabel} Key ({$keyId}).");
            }

            throw new Exception("Razorpay error: " . $errorDesc);
        } catch (Exception $e) {
            throw new Exception("Failed to connect to Razorpay: " . $e->getMessage(), 0, $e);
        }
    }

    public function verifyPayment(array $payload): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $keySecret = trim((string) ($this->gatewayModel->effective_api_secret ?: ($this->gatewayModel->api_secret ?: env('RAZORPAY_KEY_SECRET', env('RAZORPAY_SECRET')))));
        if (empty($keySecret)) {
            return true; // Mock verification
        }

        $orderId = $payload['razorpay_order_id'] ?? '';
        $paymentId = $payload['razorpay_payment_id'] ?? '';
        $signature = $payload['razorpay_signature'] ?? '';

        if (empty($orderId) || empty($paymentId) || empty($signature)) {
            return false;
        }

        $generatedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        return hash_equals($generatedSignature, $signature);
    }
}
