<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ListRow
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description = null
    ) {
        Validator::assertNotEmpty($this->id, 'id');
        Validator::assertNotEmpty($this->title, 'title');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'id' => $this->id,
            'title' => $this->title,
        ];
        if ($this->description !== null) {
            $payload['description'] = $this->description;
        }

        return $payload;
    }
}
