<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber;

final readonly class DisplayNameStatusResponse
{
    public function __construct(
        public string $id,
        public string $nameStatus
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'], $payload['name_status'])) {
            throw new \RuntimeException('Display name status response missing fields.');
        }

        return new self((string) $payload['id'], (string) $payload['name_status']);
    }
}
