<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\BlockUsers;

final readonly class BlockedUser
{
    public function __construct(public string $waId)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['wa_id'])) {
            throw new \RuntimeException('Blocked user response missing wa_id.');
        }

        return new self((string) $payload['wa_id']);
    }
}
