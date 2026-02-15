<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact\Contact;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ContactMessageRequest
{
    /**
     * @param list<Contact> $contacts
     */
    public function __construct(
        public string $to,
        public array $contacts,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        if ($this->contacts === []) {
            throw new \InvalidArgumentException('contacts must not be empty.');
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
            'to' => $this->to,
            'type' => 'contacts',
            'contacts' => array_map(
                static fn (Contact $contact): array => $contact->toArray(),
                $this->contacts
            ),
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
