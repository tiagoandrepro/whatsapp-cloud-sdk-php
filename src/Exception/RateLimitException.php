<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Exception;

class RateLimitException extends ApiException
{
    private ?int $retryAfterSeconds;

    public function __construct(
        string $message,
        int $statusCode,
        string $method,
        string $path,
        ?string $requestId = null,
        ?int $retryAfterSeconds = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $method, $path, $requestId, $previous);
        $this->retryAfterSeconds = $retryAfterSeconds;
    }

    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfterSeconds;
    }
}
