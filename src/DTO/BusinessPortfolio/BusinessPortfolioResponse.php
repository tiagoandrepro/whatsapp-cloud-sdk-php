<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\BusinessPortfolio;

final readonly class BusinessPortfolioResponse
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
