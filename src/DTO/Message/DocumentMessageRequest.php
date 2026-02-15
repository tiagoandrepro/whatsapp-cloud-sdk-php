<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class DocumentMessageRequest
{
    public function __construct(
        public string $to,
        public ?string $mediaId,
        public ?string $link,
        public ?string $caption = null,
        public ?string $filename = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        if (($this->mediaId === null && $this->link === null) || ($this->mediaId !== null && $this->link !== null)) {
            throw new \InvalidArgumentException('Either mediaId or link must be set, but not both.');
        }
        if ($this->caption !== null) {
            Validator::assertMaxLength($this->caption, 1024, 'caption');
        }
        if ($this->filename !== null) {
            Validator::assertNotEmpty($this->filename, 'filename');
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
        $document = [];
        if ($this->mediaId !== null) {
            $document['id'] = $this->mediaId;
        }
        if ($this->link !== null) {
            $document['link'] = $this->link;
        }
        if ($this->caption !== null) {
            $document['caption'] = $this->caption;
        }
        if ($this->filename !== null) {
            $document['filename'] = $this->filename;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'document',
            'document' => $document,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
