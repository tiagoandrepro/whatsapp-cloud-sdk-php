<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class LocationMessageRequest
{
    public function __construct(
        public string $to,
        public float $latitude,
        public float $longitude,
        public ?string $name = null,
        public ?string $address = null,
        public ?string $contextMessageId = null
    ) {
        Validator::assertE164($this->to, 'to');
        Validator::assertRange($this->latitude, -90.0, 90.0, 'latitude');
        Validator::assertRange($this->longitude, -180.0, 180.0, 'longitude');
        if ($this->name !== null) {
            Validator::assertNotEmpty($this->name, 'name');
        }
        if ($this->address !== null) {
            Validator::assertNotEmpty($this->address, 'address');
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
        $location = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
        if ($this->name !== null) {
            $location['name'] = $this->name;
        }
        if ($this->address !== null) {
            $location['address'] = $this->address;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'location',
            'location' => $location,
        ];

        if ($this->contextMessageId !== null) {
            $payload['context'] = ['message_id' => $this->contextMessageId];
        }

        return $payload;
    }
}
