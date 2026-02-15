<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ContactEmail
{
    public function __construct(
        public string $email,
        public ?string $type = null
    ) {
        Validator::assertNotEmpty($this->email, 'email');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'email' => $this->email,
        ];
        if ($this->type !== null) {
            $payload['type'] = $this->type;
        }

        return $payload;
    }
}
