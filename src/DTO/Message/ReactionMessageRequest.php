<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ReactionMessageRequest
{
    public function __construct(
        public string $to,
        public string $messageId,
        public string $emoji
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->messageId, 'messageId');
        Validator::assertNotEmpty($this->emoji, 'emoji');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'reaction',
            'reaction' => [
                'message_id' => $this->messageId,
                'emoji' => $this->emoji,
            ],
        ];
    }
}
