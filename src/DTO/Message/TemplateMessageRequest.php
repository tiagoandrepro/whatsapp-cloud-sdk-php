<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\DTO\Template\TemplateComponent;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class TemplateMessageRequest
{
    /**
     * @param list<TemplateComponent> $components
     */
    public function __construct(
        public string $to,
        public string $templateName,
        public string $language,
        public array $components = [],
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertNotEmpty($this->templateName, 'templateName');
        Validator::assertNotEmpty($this->language, 'language');
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
            'type' => 'template',
            'template' => [
                'name' => $this->templateName,
                'language' => ['code' => $this->language],
                'components' => array_map(
                    static fn (TemplateComponent $component): array => $component->toArray(),
                    $this->components
                ),
            ],
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
