<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Template;

final readonly class TemplateComponent
{
    /**
     * @param list<TemplateParameter> $parameters
     */
    public function __construct(
        public string $type,
        public array $parameters = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'parameters' => array_map(
                static fn (TemplateParameter $parameter): array => $parameter->toArray(),
                $this->parameters
            ),
        ];
    }
}
