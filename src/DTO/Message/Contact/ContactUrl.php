<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ContactUrl
{
    public function __construct(
        public string $url,
        public ?string $type = null
    ) {
        Validator::assertNotEmpty($this->url, 'url');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'url' => $this->url,
        ];
        if ($this->type !== null) {
            $payload['type'] = $this->type;
        }

        return $payload;
    }
}
