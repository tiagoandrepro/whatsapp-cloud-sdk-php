<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class TypingIndicatorRequest
{
    public function __construct(
        public string $messageId,
        public string $indicatorType = 'text'
    ) {
        Validator::assertNotEmpty($this->messageId, 'messageId');
        Validator::assertNotEmpty($this->indicatorType, 'indicatorType');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $this->messageId,
            'typing_indicator' => [
                'type' => $this->indicatorType,
            ],
        ];
    }
}
