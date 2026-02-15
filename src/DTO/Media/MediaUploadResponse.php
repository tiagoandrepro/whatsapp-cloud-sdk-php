<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Media;

final readonly class MediaUploadResponse
{
    public function __construct(public string $mediaId)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new \RuntimeException('Upload response missing media id.');
        }

        return new self((string) $payload['id']);
    }
}
