<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

final readonly class MessageResponse
{
    public function __construct(public string $messageId)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['messages'][0]['id'])) {
            throw new \RuntimeException('Message response missing id.');
        }

        return new self((string) $payload['messages'][0]['id']);
    }
}
