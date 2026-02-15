<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Template;

final readonly class TemplateParameter
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        public string $type,
        public ?array $data = null
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = ['type' => $this->type];
        if ($this->data !== null) {
            $payload += $this->data;
        }

        return $payload;
    }
}
