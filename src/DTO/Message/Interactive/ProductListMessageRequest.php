<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ProductListMessageRequest
{
    /**
     * @param list<ProductSection> $sections
     */
    public function __construct(
        public string $to,
        public string $catalogId,
        public array $sections,
        public string $bodyText,
        public ?string $headerText = null,
        public ?string $footerText = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->catalogId, 'catalogId');
        Validator::assertNotEmpty($this->bodyText, 'bodyText');
        if ($this->sections === []) {
            throw new \InvalidArgumentException('sections must not be empty.');
        }
        if ($this->headerText !== null) {
            Validator::assertNotEmpty($this->headerText, 'headerText');
        }
        if ($this->footerText !== null) {
            Validator::assertNotEmpty($this->footerText, 'footerText');
        }
        if ($this->contextMessageId !== null) {
            Validator::assertNotEmpty($this->contextMessageId, 'contextMessageId');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $interactive = [
            'type' => 'product_list',
            'body' => ['text' => $this->bodyText],
            'action' => [
                'catalog_id' => $this->catalogId,
                'sections' => array_map(
                    static fn (ProductSection $section): array => $section->toArray(),
                    $this->sections
                ),
            ],
        ];

        if ($this->headerText !== null) {
            $interactive['header'] = [
                'type' => 'text',
                'text' => $this->headerText,
            ];
        }
        if ($this->footerText !== null) {
            $interactive['footer'] = ['text' => $this->footerText];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
