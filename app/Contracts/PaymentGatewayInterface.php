<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function getName(): string;
    public function getCode(): string;
    public function isEnabled(): bool;
    public function createOrder(float $amount, string $orderId, array $customerDetails = []): array;
    public function verifyPayment(array $payload): bool;
}
