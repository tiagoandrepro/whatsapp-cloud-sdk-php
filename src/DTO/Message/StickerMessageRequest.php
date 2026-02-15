<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class StickerMessageRequest
{
    public function __construct(
        public string $to,
        public ?string $mediaId,
        public ?string $link,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        if (($this->mediaId === null && $this->link === null) || ($this->mediaId !== null && $this->link !== null)) {
            throw new \InvalidArgumentException('Either mediaId or link must be set, but not both.');
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
        $sticker = [];
        if ($this->mediaId !== null) {
            $sticker['id'] = $this->mediaId;
        }
        if ($this->link !== null) {
            $sticker['link'] = $this->link;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'sticker',
            'sticker' => $sticker,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
