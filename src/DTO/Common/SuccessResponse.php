<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Common;

final readonly class SuccessResponse
{
    public function __construct(public bool $success)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!array_key_exists('success', $payload)) {
            throw new \RuntimeException('Response missing success flag.');
        }

        return new self((bool) $payload['success']);
    }
}
