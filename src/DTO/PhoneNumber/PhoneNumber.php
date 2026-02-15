<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber;

final readonly class PhoneNumber
{
    public function __construct(
        public string $id,
        public ?string $displayPhoneNumber,
        public ?string $verifiedName,
        public ?string $qualityRating
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new \RuntimeException('Phone number response missing id.');
        }

        return new self(
            (string) $payload['id'],
            isset($payload['display_phone_number']) ? (string) $payload['display_phone_number'] : null,
            isset($payload['verified_name']) ? (string) $payload['verified_name'] : null,
            isset($payload['quality_rating']) ? (string) $payload['quality_rating'] : null
        );
    }
}
