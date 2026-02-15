<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload;

final readonly class UploadSessionResponse
{
    public function __construct(public string $id)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new \RuntimeException('Upload session response missing id.');
        }

        return new self((string) $payload['id']);
    }
}
