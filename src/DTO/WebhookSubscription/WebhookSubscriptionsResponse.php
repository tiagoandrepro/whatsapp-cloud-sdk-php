<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\WebhookSubscription;

final readonly class WebhookSubscriptionsResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }
}
