<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Exception;

use RuntimeException;

class ApiException extends RuntimeException
{
    private int $statusCode;
    private string $method;
    private string $path;
    private ?string $requestId;

    public function __construct(
        string $message,
        int $statusCode,
        string $method,
        string $path,
        ?string $requestId = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->method = $method;
        $this->path = $path;
        $this->requestId = $requestId;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
}
