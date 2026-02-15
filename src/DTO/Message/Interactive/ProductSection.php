<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ProductSection
{
    /**
     * @param list<ProductItem> $items
     */
    public function __construct(
        public string $title,
        public array $items
    ) {
        Validator::assertNotEmpty($this->title, 'title');
        if ($this->items === []) {
            throw new \InvalidArgumentException('items must not be empty.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'product_items' => array_map(
                static fn (ProductItem $item): array => $item->toArray(),
                $this->items
            ),
        ];
    }
}
