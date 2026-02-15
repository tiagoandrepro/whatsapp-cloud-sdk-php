<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class MediaMessageRequest
{
    public function __construct(
        public string $to,
        public MediaType $mediaType,
        public ?string $mediaId,
        public ?string $link,
        public ?string $caption = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        if (($this->mediaId === null && $this->link === null) || ($this->mediaId !== null && $this->link !== null)) {
            throw new \InvalidArgumentException('Either mediaId or link must be set, but not both.');
        }
        if ($this->caption !== null) {
            Validator::assertMaxLength($this->caption, 1024, 'caption');
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
        $mediaPayload = [];
        if ($this->mediaId !== null) {
            $mediaPayload['id'] = $this->mediaId;
        }
        if ($this->link !== null) {
            $mediaPayload['link'] = $this->link;
        }
        if ($this->caption !== null) {
            $mediaPayload['caption'] = $this->caption;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => $this->mediaType->value,
            $this->mediaType->value => $mediaPayload,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
