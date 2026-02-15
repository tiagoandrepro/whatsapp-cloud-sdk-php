<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ProductMessageRequest
{
    public function __construct(
        public string $to,
        public string $catalogId,
        public string $productRetailerId,
        public ?string $bodyText = null,
        public ?string $footerText = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->catalogId, 'catalogId');
        Validator::assertNotEmpty($this->productRetailerId, 'productRetailerId');
        if ($this->bodyText !== null) {
            Validator::assertNotEmpty($this->bodyText, 'bodyText');
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
            'type' => 'product',
            'action' => [
                'catalog_id' => $this->catalogId,
                'product_retailer_id' => $this->productRetailerId,
            ],
        ];

        if ($this->bodyText !== null) {
            $interactive['body'] = ['text' => $this->bodyText];
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
