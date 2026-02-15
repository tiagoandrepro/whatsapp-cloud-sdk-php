<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class TextMessageRequest
{
    public function __construct(
        public string $to,
        public string $text,
        public bool $previewUrl = false,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->text, 'text');
        Validator::assertMaxLength($this->text, 4096, 'text');
        if ($this->contextMessageId !== null) {
            Validator::assertNotEmpty($this->contextMessageId, 'contextMessageId');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => 'text',
            'text' => [
                'body' => $this->text,
                'preview_url' => $this->previewUrl,
            ],
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
