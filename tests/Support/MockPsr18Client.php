<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Support;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MockPsr18Client implements ClientInterface
{
    /** @var list<ResponseInterface> */
    private array $responses;
    private ?RequestInterface $lastRequest = null;
    private int $requestCount = 0;

    /**
     * @param list<ResponseInterface> $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;
        $this->requestCount++;
        if ($this->responses === []) {
            throw new \RuntimeException('No mock responses left.');
        }

        return array_shift($this->responses);
    }

    public function getLastRequest(): ?RequestInterface
    {
        return $this->lastRequest;
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }
}
