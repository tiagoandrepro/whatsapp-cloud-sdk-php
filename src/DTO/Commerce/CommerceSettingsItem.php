<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Commerce;

final readonly class CommerceSettingsItem
{
    public function __construct(
        public ?bool $isCartEnabled,
        public ?bool $isCatalogVisible,
        public ?string $id
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            isset($payload['is_cart_enabled']) ? (bool) $payload['is_cart_enabled'] : null,
            isset($payload['is_catalog_visible']) ? (bool) $payload['is_catalog_visible'] : null,
            isset($payload['id']) ? (string) $payload['id'] : null
        );
    }
}
