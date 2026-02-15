<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Flow;

final readonly class FlowAssetsResponse
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
