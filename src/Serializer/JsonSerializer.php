<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Serializer;

use JsonException;

final class JsonSerializer
{
    /**
     * @param array<string, mixed> $data
     */
    public function encode(array $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new \RuntimeException('Failed to encode JSON payload.', 0, $exception);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function decode(string $json): array
    {
        try {
            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new \RuntimeException('Failed to decode JSON response.', 0, $exception);
        }

        if (!is_array($decoded)) {
            throw new \RuntimeException('JSON response is not an object.');
        }

        return $decoded;
    }
}
