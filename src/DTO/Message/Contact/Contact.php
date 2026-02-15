<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

final readonly class Contact
{
    /**
     * @param list<ContactPhone> $phones
     * @param list<ContactEmail> $emails
     * @param list<ContactAddress> $addresses
     * @param list<ContactUrl> $urls
     */
    public function __construct(
        public ContactName $name,
        public array $phones = [],
        public array $emails = [],
        public array $addresses = [],
        public ?ContactOrg $org = null,
        public ?string $birthday = null,
        public array $urls = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'name' => $this->name->toArray(),
        ];

        if ($this->phones !== []) {
            $payload['phones'] = array_map(
                static fn (ContactPhone $phone): array => $phone->toArray(),
                $this->phones
            );
        }

        if ($this->emails !== []) {
            $payload['emails'] = array_map(
                static fn (ContactEmail $email): array => $email->toArray(),
                $this->emails
            );
        }

        if ($this->addresses !== []) {
            $payload['addresses'] = array_map(
                static fn (ContactAddress $address): array => $address->toArray(),
                $this->addresses
            );
        }

        if ($this->org !== null) {
            $payload['org'] = $this->org->toArray();
        }

        if ($this->birthday !== null) {
            $payload['birthday'] = $this->birthday;
        }

        if ($this->urls !== []) {
            $payload['urls'] = array_map(
                static fn (ContactUrl $url): array => $url->toArray(),
                $this->urls
            );
        }

        return $payload;
    }
}
