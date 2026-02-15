<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class AudioMessageRequest
{
    public function __construct(
        public string $to,
        public ?string $mediaId,
        public ?string $link,
        public ?bool $voice = null,
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
        $audio = [];
        if ($this->mediaId !== null) {
            $audio['id'] = $this->mediaId;
        }
        if ($this->link !== null) {
            $audio['link'] = $this->link;
        }
        if ($this->voice !== null) {
            $audio['voice'] = $this->voice;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'audio',
            'audio' => $audio,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
