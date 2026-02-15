<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ReplyButton
{
    public function __construct(
        public string $id,
        public string $title
    ) {
        Validator::assertNotEmpty($this->id, 'id');
        Validator::assertNotEmpty($this->title, 'title');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => 'reply',
            'reply' => [
                'id' => $this->id,
                'title' => $this->title,
            ],
        ];
    }
}
