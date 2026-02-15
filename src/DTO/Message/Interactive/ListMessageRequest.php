<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ListMessageRequest
{
    /**
     * @param list<ListSection> $sections
     */
    public function __construct(
        public string $to,
        public string $buttonText,
        public string $bodyText,
        public array $sections,
        public ?string $headerText = null,
        public ?string $footerText = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->buttonText, 'buttonText');
        Validator::assertNotEmpty($this->bodyText, 'bodyText');
        if ($this->sections === []) {
            throw new \InvalidArgumentException('sections must not be empty.');
        }
        if ($this->headerText !== null) {
            Validator::assertNotEmpty($this->headerText, 'headerText');
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
            'type' => 'list',
            'body' => ['text' => $this->bodyText],
            'action' => [
                'button' => $this->buttonText,
                'sections' => array_map(
                    static fn (ListSection $section): array => $section->toArray(),
                    $this->sections
                ),
            ],
        ];

        if ($this->headerText !== null) {
            $interactive['header'] = [
                'type' => 'text',
                'text' => $this->headerText,
            ];
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
