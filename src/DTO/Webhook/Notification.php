<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Webhook;

final readonly class Notification
{
    /**
     * @param array<string, mixed> $value
     */
    public function __construct(
        public string $field,
        public array $value,
        public ?string $entryId
    ) {
    }
}
