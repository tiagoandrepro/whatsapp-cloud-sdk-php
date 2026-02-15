<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Billing;

final readonly class CreditLine
{
    public function __construct(
        public string $id,
        public ?string $legalEntityName
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new \RuntimeException('Credit line response missing id.');
        }

        return new self(
            (string) $payload['id'],
            isset($payload['legal_entity_name']) ? (string) $payload['legal_entity_name'] : null
        );
    }
}
