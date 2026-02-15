<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload;

final readonly class UploadHandleResponse
{
    public function __construct(public string $handle)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['h'])) {
            throw new \RuntimeException('Upload handle response missing handle.');
        }

        return new self((string) $payload['h']);
    }
}
