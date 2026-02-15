<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

final readonly class ContactAddress
{
    public function __construct(
        public ?string $street = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $country = null,
        public ?string $countryCode = null,
        public ?string $type = null
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];
        if ($this->street !== null) {
            $payload['street'] = $this->street;
        }
        if ($this->city !== null) {
            $payload['city'] = $this->city;
        }
        if ($this->state !== null) {
            $payload['state'] = $this->state;
        }
        if ($this->zip !== null) {
            $payload['zip'] = $this->zip;
        }
        if ($this->country !== null) {
            $payload['country'] = $this->country;
        }
        if ($this->countryCode !== null) {
            $payload['country_code'] = $this->countryCode;
        }
        if ($this->type !== null) {
            $payload['type'] = $this->type;
        }

        return $payload;
    }
}
