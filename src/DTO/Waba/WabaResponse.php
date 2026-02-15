<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Waba;

final readonly class WabaResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }
}
