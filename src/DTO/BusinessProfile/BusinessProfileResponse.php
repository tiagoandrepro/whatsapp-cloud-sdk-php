<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\BusinessProfile;

final readonly class BusinessProfileResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }
}
