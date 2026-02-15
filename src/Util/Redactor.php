<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Util;

final class Redactor
{
    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function sanitizeContext(array $context): array
    {
        $sanitized = [];
        foreach ($context as $key => $value) {
            $keyLower = strtolower((string) $key);
            if (str_contains($keyLower, 'authorization') || str_contains($keyLower, 'token')) {
                $sanitized[$key] = '[redacted]';
                continue;
            }

            if (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeContext($value);
                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    public function sanitizeString(string $value): string
    {
        $masked = preg_replace('/\+?\d{8,15}/', '[redacted-number]', $value);
        if ($masked === null) {
            return $value;
        }

        return $masked;
    }
}
