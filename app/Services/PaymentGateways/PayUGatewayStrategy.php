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

        $merchantKey = $this->gatewayModel->api_key;
        $merchantSalt = $this->gatewayModel->api_secret;
        $isLive = $this->gatewayModel->mode === 'live';

        $txnid = 'txnid_' . rand(100000, 999999);
        $productInfo = $customerDetails['product_info'] ?? 'Hotel Subscription';
        $firstName = $customerDetails['name'] ?? 'Hotel Admin';
        $email = $customerDetails['email'] ?? '';
        $phone = $customerDetails['phone'] ?? '';

        if (empty($merchantKey) || empty($merchantSalt)) {
            // Mock simulation
            return [
                'success' => true,
                'order_id' => $txnid,
                'gateway_reference' => 'payu_ref_' . rand(100000, 999999),
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

        $merchantSalt = $this->gatewayModel->api_secret;
        $merchantKey = $this->gatewayModel->api_key;

        $status = $payload['status'] ?? '';
        $txnid = $payload['txnid'] ?? '';
        $amount = $payload['amount'] ?? '';
        $productInfo = $payload['productinfo'] ?? '';
        $firstName = $payload['firstname'] ?? '';
        $email = $payload['email'] ?? '';
        $receivedHash = $payload['hash'] ?? '';
        $udf1 = $payload['udf1'] ?? '';
        $udf2 = $payload['udf2'] ?? '';
        $udf3 = $payload['udf3'] ?? '';
        $udf4 = $payload['udf4'] ?? '';
        $udf5 = $payload['udf5'] ?? '';

        if (empty($merchantSalt) || empty($receivedHash)) {
            return $status === 'success';
        }

        // Reverse hash calculation for PayU response verification:
        // salt|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key
        $hashString = "{$merchantSalt}|{$status}||||||{$udf5}|{$udf4}|{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstName}|{$productInfo}|{$amount}|{$txnid}|{$merchantKey}";
        $calculatedHash = strtolower(hash('sha512', $hashString));

        return $status === 'success' && hash_equals($calculatedHash, $receivedHash);
    }
}
