<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use Exception;

class PayUGatewayStrategy implements PaymentGatewayInterface
{
    protected ?PaymentGateway $gatewayModel;

    public function __construct()
    {
        $this->gatewayModel = PaymentGateway::getForCode('payu');
    }

    public function getName(): string
    {
        return 'PayU';
    }

    public function getCode(): string
    {
        return 'payu';
    }

    public function isEnabled(): bool
    {
        return $this->gatewayModel && $this->gatewayModel->is_enabled;
    }

    public function createOrder(float $amount, string $orderId, array $customerDetails = []): array
    {
        if (!$this->isEnabled()) {
            throw new Exception("PayU gateway is disabled.");
        }

        $merchantKey = trim((string) ($this->gatewayModel->effective_api_key ?: $this->gatewayModel->api_key));
        $merchantSalt = trim((string) ($this->gatewayModel->effective_api_secret ?: $this->gatewayModel->api_secret));
        $isLive = $this->gatewayModel->mode === 'live';

        $txnid = 'txnid_' . strtoupper(\Illuminate\Support\Str::random(12));
        $productInfo = $customerDetails['product_info'] ?? 'Hotel Subscription';
        $firstName = $customerDetails['name'] ?? 'Hotel Admin';
        $email = $customerDetails['email'] ?? '';
        $phone = $customerDetails['phone'] ?? '';

        if (empty($merchantKey) || empty($merchantSalt)) {
            if (!app()->environment(['local', 'testing'])) {
                throw new Exception('PayU gateway credentials are not configured.');
            }
            // Mock simulation permitted ONLY in local/testing
            $mockTxnId = 'txnid_mock_' . strtoupper(\Illuminate\Support\Str::random(10));
            return [
                'success' => true,
                'order_id' => $mockTxnId,
                'gateway_reference' => 'payu_ref_' . bin2hex(random_bytes(4)),
                'amount' => $amount,
                'currency' => 'INR',
                'checkout_url' => '#',
            ];
        }

        $udf1 = $customerDetails['udf1'] ?? '';
        $udf2 = $customerDetails['udf2'] ?? '';
        $udf3 = $customerDetails['udf3'] ?? '';
        $udf4 = $customerDetails['udf4'] ?? '';
        $udf5 = $customerDetails['udf5'] ?? '';

        // PayU Hash formula: sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
        $hashString = "{$merchantKey}|{$txnid}|{$amount}|{$productInfo}|{$firstName}|{$email}|{$udf1}|{$udf2}|{$udf3}|{$udf4}|{$udf5}||||||{$merchantSalt}";
        $hash = strtolower(hash('sha512', $hashString));

        $baseUrl = $isLive
            ? 'https://secure.payu.in/_payment'
            : 'https://test.payu.in/_payment';

        return [
            'success' => true,
            'order_id' => $txnid,
            'gateway_reference' => $txnid,
            'amount' => $amount,
            'currency' => 'INR',
            'checkout_url' => $baseUrl,
            'payu_params' => [
                'key' => $merchantKey,
                'txnid' => $txnid,
                'amount' => $amount,
                'productinfo' => $productInfo,
                'firstname' => $firstName,
                'email' => $email,
                'phone' => $phone,
                'udf1' => $udf1,
                'udf2' => $udf2,
                'udf3' => $udf3,
                'udf4' => $udf4,
                'udf5' => $udf5,
                'hash' => $hash,
                'surl' => route('payment.success'),
                'furl' => route('payment.failure'),
            ],
        ];
    }

    public function verifyPayment(array $payload): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $merchantSalt = trim((string) ($this->gatewayModel->effective_api_secret ?: $this->gatewayModel->api_secret));
        $merchantKey = trim((string) ($this->gatewayModel->effective_api_key ?: $this->gatewayModel->api_key));

        $status = (string) ($payload['status'] ?? '');
        $txnid = (string) ($payload['txnid'] ?? '');
        $amount = (string) ($payload['amount'] ?? '');
        $productInfo = (string) ($payload['productinfo'] ?? '');
        $firstName = (string) ($payload['firstname'] ?? '');
        $email = (string) ($payload['email'] ?? '');
        $receivedHash = (string) ($payload['hash'] ?? '');
        $additionalCharges = (string) ($payload['additionalCharges'] ?? '');
        $udf1 = (string) ($payload['udf1'] ?? '');
        $udf2 = (string) ($payload['udf2'] ?? '');
        $udf3 = (string) ($payload['udf3'] ?? '');
        $udf4 = (string) ($payload['udf4'] ?? '');
        $udf5 = (string) ($payload['udf5'] ?? '');

        if ($merchantKey === '' || $merchantSalt === '' || $receivedHash === '') {
            if (app()->environment(['local', 'testing']) && str_starts_with($txnid, 'txnid_mock_')) {
                return strtolower($status) === 'success';
            }
            return false;
        }

        // Reverse hash calculation for PayU response verification:
        // [additionalCharges|]salt|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key
        $hashString = "{$merchantSalt}|{$status}||||||{$udf5}|{$udf4}|{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstName}|{$productInfo}|{$amount}|{$txnid}|{$merchantKey}";
        if ($additionalCharges !== '') {
            $hashString = "{$additionalCharges}|{$hashString}";
        }
        $calculatedHash = strtolower(hash('sha512', $hashString));

        return strtolower($status) === 'success' && hash_equals($calculatedHash, strtolower($receivedHash));
    }
}
