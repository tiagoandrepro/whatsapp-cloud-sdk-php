<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload;

final readonly class UploadStatusResponse
{
    public function __construct(
        public string $id,
        public int $fileOffset
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'], $payload['file_offset'])) {
            throw new \RuntimeException('Upload status response missing fields.');
        }

        return new self((string) $payload['id'], (int) $payload['file_offset']);
    }
}
