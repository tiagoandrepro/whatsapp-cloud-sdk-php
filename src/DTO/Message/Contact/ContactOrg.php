<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

final readonly class ContactOrg
{
    public function __construct(
        public ?string $company = null,
        public ?string $department = null,
        public ?string $title = null
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];
        if ($this->company !== null) {
            $payload['company'] = $this->company;
        }
        if ($this->department !== null) {
            $payload['department'] = $this->department;
        }
        if ($this->title !== null) {
            $payload['title'] = $this->title;
        }

        return $payload;
    }
}
