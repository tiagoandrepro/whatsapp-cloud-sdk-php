<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Transport;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tiagoandrepro\WhatsAppCloud\Auth\TokenProviderInterface;
use Tiagoandrepro\WhatsAppCloud\Exception\TransportException;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class Psr18Transport
{
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private TokenProviderInterface $tokenProvider;
    private JsonSerializer $serializer;
    private ErrorMapper $errorMapper;
    private SafeLogger $logger;
    private RetryPolicy $retryPolicy;
    private string $baseUrl;
    private string $graphApiVersion;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        TokenProviderInterface $tokenProvider,
        JsonSerializer $serializer,
        ErrorMapper $errorMapper,
        SafeLogger $logger,
        RetryPolicy $retryPolicy,
        string $baseUrl,
        string $graphApiVersion
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->tokenProvider = $tokenProvider;
        $this->serializer = $serializer;
        $this->errorMapper = $errorMapper;
        $this->logger = $logger;
        $this->retryPolicy = $retryPolicy;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->graphApiVersion = trim($graphApiVersion, '/');
    }

    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, string> $headers
     * @return array<string, mixed>
     */
    public function requestJson(string $method, string $path, ?array $payload = null, array $headers = []): array
    {
        $response = $this->request($method, $path, $payload, $headers);
        return $this->serializer->decode((string) $response->getBody());
    }

    /**
     * @param array<string, string> $headers
     */
    public function requestRaw(string $method, string $path, string $body, array $headers = []): ResponseInterface
    {
        return $this->requestWithBody($method, $this->buildUrl($path), $body, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function requestAbsolute(string $method, string $url, array $headers = []): ResponseInterface
    {
        return $this->requestWithBody($method, $url, null, $headers);
    }

    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, string> $headers
     */
    public function request(string $method, string $path, ?array $payload = null, array $headers = []): ResponseInterface
    {
        $attempts = 0;

        $url = $this->buildUrl($path);

        do {
            $attempts++;
            $request = $this->buildJsonRequest($method, $url, $payload, $headers);

            try {
                $response = $this->client->sendRequest($request);
            } catch (ClientExceptionInterface $exception) {
                throw new TransportException('HTTP client error.', 0, $exception);
            }

            $statusCode = $response->getStatusCode();
            if ($statusCode < 400) {
                return $response;
            }

            $requestId = $this->extractRequestId($response);
            $retryAfterSeconds = $this->extractRetryAfter($response);
            $body = null;

            $bodyString = (string) $response->getBody();
            if ($bodyString !== '') {
                try {
                    $body = $this->serializer->decode($bodyString);
                } catch (\RuntimeException) {
                    $body = null;
                }
            }

            if (!$this->retryPolicy->shouldRetry($statusCode) || $attempts > $this->retryPolicy->getMaxRetries()) {
                throw $this->errorMapper->map($statusCode, $body, $method, $url, $requestId, $retryAfterSeconds);
            }

            $delayMs = $this->retryPolicy->getDelayMs($attempts);
            $this->logger->warning('Retrying request after backoff.', [
                'attempt' => $attempts,
                'delay_ms' => $delayMs,
                'status' => $statusCode,
            ]);
            usleep($delayMs * 1000);
        } while (true);
    }

    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, string> $headers
     */
    private function buildJsonRequest(string $method, string $url, ?array $payload, array $headers): \Psr\Http\Message\RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $url)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $this->tokenProvider->getToken());

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        if ($payload !== null) {
            $json = $this->serializer->encode($payload);
            $stream = $this->streamFactory->createStream($json);
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($stream);
        }

        return $request;
    }

    /**
     * @param array<string, string> $headers
     */
    private function requestWithBody(string $method, string $url, ?string $body, array $headers): ResponseInterface
    {
        $attempts = 0;

        do {
            $attempts++;
            $request = $this->requestFactory->createRequest($method, $url)
                ->withHeader('Authorization', 'Bearer ' . $this->tokenProvider->getToken());

            foreach ($headers as $header => $value) {
                $request = $request->withHeader($header, $value);
            }

            if ($body !== null) {
                $stream = $this->streamFactory->createStream($body);
                $request = $request->withBody($stream);
            }

            try {
                $response = $this->client->sendRequest($request);
            } catch (ClientExceptionInterface $exception) {
                throw new TransportException('HTTP client error.', 0, $exception);
            }

            $statusCode = $response->getStatusCode();
            if ($statusCode < 400) {
                return $response;
            }

            $requestId = $this->extractRequestId($response);
            $retryAfterSeconds = $this->extractRetryAfter($response);
            $bodyPayload = null;

            $bodyString = (string) $response->getBody();
            if ($bodyString !== '') {
                try {
                    $bodyPayload = $this->serializer->decode($bodyString);
                } catch (\RuntimeException) {
                    $bodyPayload = null;
                }
            }

            if (!$this->retryPolicy->shouldRetry($statusCode) || $attempts > $this->retryPolicy->getMaxRetries()) {
                throw $this->errorMapper->map($statusCode, $bodyPayload, $method, $url, $requestId, $retryAfterSeconds);
            }

            $delayMs = $this->retryPolicy->getDelayMs($attempts);
            $this->logger->warning('Retrying request after backoff.', [
                'attempt' => $attempts,
                'delay_ms' => $delayMs,
                'status' => $statusCode,
            ]);
            usleep($delayMs * 1000);
        } while (true);
    }

    private function buildUrl(string $path): string
    {
        return $this->baseUrl . '/' . $this->graphApiVersion . '/' . ltrim($path, '/');
    }

    private function extractRequestId(ResponseInterface $response): ?string
    {
        $header = $response->getHeaderLine('x-fb-request-id');
        return $header !== '' ? $header : null;
    }

    private function extractRetryAfter(ResponseInterface $response): ?int
    {
        $retryAfter = $response->getHeaderLine('Retry-After');
        if ($retryAfter === '') {
            return null;
        }

        if (is_numeric($retryAfter)) {
            return (int) $retryAfter;
        }

        return null;
    }
}
