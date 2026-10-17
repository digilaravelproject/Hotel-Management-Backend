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

        $keyId = $this->gatewayModel->api_key ?: env('RAZORPAY_KEY_ID', env('RAZORPAY_KEY'));
        $keySecret = $this->gatewayModel->api_secret ?: env('RAZORPAY_KEY_SECRET', env('RAZORPAY_SECRET'));

        // Convert amount to paise for Razorpay
        $amountInPaise = (int) round($amount * 100);

        if (empty($keyId) || empty($keySecret)) {
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

            throw new Exception("Razorpay error: " . $response->body());
        } catch (Exception $e) {
            throw new Exception("Failed to connect to Razorpay: " . $e->getMessage(), 0, $e);
        }
    }

    public function verifyPayment(array $payload): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $keySecret = $this->gatewayModel->api_secret ?: env('RAZORPAY_KEY_SECRET', env('RAZORPAY_SECRET'));
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
