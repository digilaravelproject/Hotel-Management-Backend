<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use App\Services\IciciPaymentService;

class IciciGatewayStrategy implements PaymentGatewayInterface
{
    protected IciciPaymentService $iciciService;
    protected ?PaymentGateway $gatewayModel;

    public function __construct(IciciPaymentService $iciciService)
    {
        $this->iciciService = $iciciService;
        $this->gatewayModel = PaymentGateway::getForCode('icici');
    }

    public function getName(): string
    {
        return 'ICICI Payment Gateway';
    }

    public function getCode(): string
    {
        return 'icici';
    }

    public function isEnabled(): bool
    {
        return $this->gatewayModel && $this->gatewayModel->is_enabled;
    }

    public function createOrder(float $amount, string $orderId, array $customerDetails = []): array
    {
        return $this->iciciService->createOrder($amount, $orderId, $customerDetails);
    }

    public function verifyPayment(array $payload): bool
    {
        return $this->iciciService->verifyPayment($payload);
    }
}
