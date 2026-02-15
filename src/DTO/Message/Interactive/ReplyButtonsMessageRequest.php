<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ReplyButtonsMessageRequest
{
    /**
     * @param list<ReplyButton> $buttons
     */
    public function __construct(
        public string $to,
        public string $bodyText,
        public array $buttons,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->bodyText, 'bodyText');
        if (count($this->buttons) < 1 || count($this->buttons) > 3) {
            throw new \InvalidArgumentException('buttons must contain 1 to 3 items.');
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
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $this->bodyText],
                'action' => [
                    'buttons' => array_map(
                        static fn (ReplyButton $button): array => $button->toArray(),
                        $this->buttons
                    ),
                ],
            ],
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
