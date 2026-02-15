<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\QrCode;

final readonly class QrCode
{
    public function __construct(
        public string $code,
        public ?string $prefilledMessage,
        public ?string $deepLinkUrl,
        public ?string $qrImageUrl
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['code'])) {
            throw new \RuntimeException('QR code response missing code.');
        }

        return new self(
            (string) $payload['code'],
            isset($payload['prefilled_message']) ? (string) $payload['prefilled_message'] : null,
            isset($payload['deep_link_url']) ? (string) $payload['deep_link_url'] : null,
            isset($payload['qr_image_url']) ? (string) $payload['qr_image_url'] : null
        );
    }
}
