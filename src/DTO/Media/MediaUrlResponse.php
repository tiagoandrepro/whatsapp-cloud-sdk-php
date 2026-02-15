<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Media;

final readonly class MediaUrlResponse
{
    public function __construct(
        public string $url,
        public ?string $mimeType,
        public ?string $sha256,
        public ?int $fileSize
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['url'])) {
            throw new \RuntimeException('Media URL response missing url.');
        }

        return new self(
            (string) $payload['url'],
            isset($payload['mime_type']) ? (string) $payload['mime_type'] : null,
            isset($payload['sha256']) ? (string) $payload['sha256'] : null,
            isset($payload['file_size']) ? (int) $payload['file_size'] : null
        );
    }
}
