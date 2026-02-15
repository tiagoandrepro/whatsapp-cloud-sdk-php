<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\QrCode;

final readonly class QrCodeResponse
{
    public function __construct(public QrCode $qrCode)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (isset($payload['data']) && is_array($payload['data']) && isset($payload['data'][0]) && is_array($payload['data'][0])) {
            return new self(QrCode::fromArray($payload['data'][0]));
        }

        return new self(QrCode::fromArray($payload));
    }
}
