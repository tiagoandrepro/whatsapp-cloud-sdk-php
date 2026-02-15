<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Billing;

final readonly class CreditLinesResponse
{
    /**
     * @param list<CreditLine> $items
     */
    public function __construct(public array $items)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['data']) || !is_array($payload['data'])) {
            throw new \RuntimeException('Credit lines response missing data.');
        }

        $items = [];
        foreach ($payload['data'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $items[] = CreditLine::fromArray($item);
        }

        return new self($items);
    }
}
