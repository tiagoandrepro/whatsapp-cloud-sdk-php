<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ProductItem
{
    public function __construct(public string $productRetailerId)
    {
        Validator::assertNotEmpty($this->productRetailerId, 'productRetailerId');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_retailer_id' => $this->productRetailerId,
        ];
    }
}
