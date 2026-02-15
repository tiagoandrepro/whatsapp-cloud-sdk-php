<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class CatalogMessageRequest
{
    public function __construct(
        public string $to,
        public string $bodyText,
        public string $thumbnailProductRetailerId,
        public ?string $footerText = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->bodyText, 'bodyText');
        Validator::assertNotEmpty($this->thumbnailProductRetailerId, 'thumbnailProductRetailerId');
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
            'type' => 'catalog_message',
            'body' => ['text' => $this->bodyText],
            'action' => [
                'name' => 'catalog_message',
                'parameters' => [
                    'thumbnail_product_retailer_id' => $this->thumbnailProductRetailerId,
                ],
            ],
        ];

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
