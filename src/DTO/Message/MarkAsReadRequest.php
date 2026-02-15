<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class MarkAsReadRequest
{
    public function __construct(public string $messageId)
    {
        Validator::assertNotEmpty($this->messageId, 'messageId');
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
        ];
    }
}
