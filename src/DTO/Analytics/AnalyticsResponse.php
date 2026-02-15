<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Analytics;

final readonly class AnalyticsResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }
}
