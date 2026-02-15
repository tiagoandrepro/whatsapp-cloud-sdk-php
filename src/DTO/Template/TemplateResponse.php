<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Template;

final readonly class TemplateResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }
}
