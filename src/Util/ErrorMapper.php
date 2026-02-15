<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Util;

use Tiagoandrepro\WhatsAppCloud\Exception\ApiException;
use Tiagoandrepro\WhatsAppCloud\Exception\AuthException;
use Tiagoandrepro\WhatsAppCloud\Exception\ConflictException;
use Tiagoandrepro\WhatsAppCloud\Exception\NotFoundException;
use Tiagoandrepro\WhatsAppCloud\Exception\RateLimitException;
use Tiagoandrepro\WhatsAppCloud\Exception\ServerException;
use Tiagoandrepro\WhatsAppCloud\Exception\ValidationException;

final class ErrorMapper
{
    private Redactor $redactor;

    public function __construct(?Redactor $redactor = null)
    {
        $this->redactor = $redactor ?? new Redactor();
    }

    /**
     * @param array<string, mixed>|null $body
     */
    public function map(
        int $statusCode,
        ?array $body,
        string $method,
        string $path,
        ?string $requestId,
        ?int $retryAfterSeconds
    ): ApiException {
        $message = $this->extractMessage($body);

        if ($statusCode === 400 || $statusCode === 422) {
            return new ValidationException($message, $statusCode, $method, $path, $requestId);
        }

        if ($statusCode === 401 || $statusCode === 403) {
            return new AuthException($message, $statusCode, $method, $path, $requestId);
        }

        if ($statusCode === 404) {
            return new NotFoundException($message, $statusCode, $method, $path, $requestId);
        }

        if ($statusCode === 409) {
            return new ConflictException($message, $statusCode, $method, $path, $requestId);
        }

        if ($statusCode === 429) {
            return new RateLimitException($message, $statusCode, $method, $path, $requestId, $retryAfterSeconds);
        }

        return new ServerException($message, $statusCode, $method, $path, $requestId);
    }

    /**
     * @param array<string, mixed>|null $body
     */
    private function extractMessage(?array $body): string
    {
        $message = 'API request failed.';
        if ($body === null) {
            return $message;
        }

        if (isset($body['error']) && is_array($body['error']) && isset($body['error']['message'])) {
            $message = (string) $body['error']['message'];
        } elseif (isset($body['message'])) {
            $message = (string) $body['message'];
        }

        return $this->redactor->sanitizeString($message);
    }
}
