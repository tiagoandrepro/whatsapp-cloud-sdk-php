<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class RegistrationEndpoint extends AbstractEndpoint
{
    public function register(string $pin): SuccessResponse
    {
        $this->assertPin($pin);

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/register', [
            'messaging_product' => 'whatsapp',
            'pin' => $pin,
        ]);

        return SuccessResponse::fromArray($payload);
    }

    public function deregister(): SuccessResponse
    {
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/deregister');

        return SuccessResponse::fromArray($payload);
    }

    private function assertPin(string $pin): void
    {
        Validator::assertNotEmpty($pin, 'pin');
        if (!preg_match('/^\d{6}$/', $pin)) {
            throw new \InvalidArgumentException('pin must be a 6-digit numeric string.');
        }
    }
}
