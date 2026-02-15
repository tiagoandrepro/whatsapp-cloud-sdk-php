<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\BlockUsers;

final readonly class BlockUsersActionResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self($payload);
    }
}
