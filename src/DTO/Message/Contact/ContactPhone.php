<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ContactPhone
{
    public function __construct(
        public string $phone,
        public ?string $waId = null,
        public ?string $type = null
    ) {
        Validator::assertNotEmpty($this->phone, 'phone');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'phone' => $this->phone,
        ];
        if ($this->waId !== null) {
            $payload['wa_id'] = $this->waId;
        }
        if ($this->type !== null) {
            $payload['type'] = $this->type;
        }

        return $payload;
    }
}
