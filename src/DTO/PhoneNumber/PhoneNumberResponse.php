<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber;

final readonly class PhoneNumberResponse
{
    public function __construct(public PhoneNumber $phoneNumber)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(PhoneNumber::fromArray($payload));
    }
}
