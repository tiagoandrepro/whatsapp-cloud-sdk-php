<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive;

use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final readonly class ListSection
{
    /**
     * @param list<ListRow> $rows
     */
    public function __construct(
        public string $title,
        public array $rows
    ) {
        Validator::assertNotEmpty($this->title, 'title');
        if ($this->rows === []) {
            throw new \InvalidArgumentException('rows must not be empty.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'rows' => array_map(
                static fn (ListRow $row): array => $row->toArray(),
                $this->rows
            ),
        ];
    }
}
