<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ContactName
{
    public function __construct(
        public string $formattedName,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $middleName = null,
        public ?string $suffix = null,
        public ?string $prefix = null
    ) {
        Validator::assertNotEmpty($this->formattedName, 'formattedName');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'formatted_name' => $this->formattedName,
        ];

        if ($this->firstName !== null) {
            $payload['first_name'] = $this->firstName;
        }
        if ($this->lastName !== null) {
            $payload['last_name'] = $this->lastName;
        }
        if ($this->middleName !== null) {
            $payload['middle_name'] = $this->middleName;
        }
        if ($this->suffix !== null) {
            $payload['suffix'] = $this->suffix;
        }
        if ($this->prefix !== null) {
            $payload['prefix'] = $this->prefix;
        }

        return $payload;
    }
}
